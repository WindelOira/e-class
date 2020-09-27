<template>
    <div>
        <vs-row v-if="consolidated_grades" class="mb-4">
            <vs-col>
                <h3 class="my-1">{{ consolidated_grades.name }} Consolidated Grades</h3>
                <p class="mb-3">
                    <router-link :to="{ name: 'sections' }">
                        <small>&laquo; Return to all sections</small>
                    </router-link>
                </p>
            </vs-col>
        </vs-row>

        <div v-if="consolidated_grades.grading_sheets.length">
            <vs-tabs>
                <vs-tab v-for="(grading_sheet, indexg) in consolidated_grades.grading_sheets" :key="indexg" :label="grading_sheet.subject_id.name">
                    <div>
                        <vs-table :data="grading_sheet.grades">
                            <template slot="header">
                                <h3>{{ grading_sheet.subject_id.name }}</h3>
                            </template>
                            <template slot="thead">
                                <vs-th>Student</vs-th>
                                <vs-th>First Quarter</vs-th>
                                <vs-th>Second Quarter</vs-th>
                                <vs-th>Final Grade</vs-th>
                            </template>

                            <template slot-scope="{data}">
                                <vs-tr v-for="(tr, indextr) in grading_sheet.grades" :key="indextr">
                                    <vs-td>{{ grading_sheet.grades[indextr].student_id.name }}</vs-td>
                                    <vs-td>{{ studentQuarterlyGrade(indextr, 'first').grade }}</vs-td>
                                    <vs-td>{{ studentQuarterlyGrade(indextr, 'second').grade }}</vs-td>
                                    <vs-td>{{ studentFinalsGrade(indextr) }}</vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </vs-tab>
            </vs-tabs>

            <vs-button @click="exportGrades">Export</vs-button>
        </div>
        <vs-alert v-else color="danger" icon="warning">
            <span>No grading sheet found for this section on this semester, create <router-link :to="{ name: 'grading-sheet_new', params: { section: $route.params.id, level: consolidated_grades.level_id, adviser: consolidated_grades.user_id, semester: $route.params.semester } }">here</router-link>.</span>
        </vs-alert>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import XLSX from 'xlsx'

    export default {
        data() {
            return {
                consolidated_grades     : {
                    grading_sheets          : []
                },
                tracks                  : [
                    { key: 'written_work' },
                    { key: 'performance_task' },
                    { key: 'quarterly_assessment' }
                ],
                vars                    : {
                    headers     : {
                        written_work            : {
                            title                   : 'Written Work',
                            percentage              : 0
                        },
                        performance_task        : {
                            title                   : 'Performance Task',
                            percentage              : 0
                        },
                        quarterly_assessment    : {
                            title                   : 'Quarterly Assessment',
                            percentage              : 0
                        }
                    }
                }
            }
        },
        computed    : {
            ...mapGetters([
                'settings'
            ]),
            hpsTotal() {
                return (type, quarter) => {
                    var hps = this.consolidated_grades.grading_sheets[0].hps[type][quarter]

                    return hps.reduce((total, num) => {
                        return parseFloat(total) + parseFloat(num)
                    })
                }
            },
            studentGSTotal() {
                return (student, type, quarter) => {
                    var gs = this.consolidated_grades.grading_sheets[0].grades[student][type].quarters[quarter]

                    return gs.reduce((total, num) => {
                        return parseFloat(total) + parseFloat(num)
                    })
                }
            },
            studentPSTotal() {
                return (student, type, quarter) => {
                    var hpsTotal = parseFloat( this.hpsTotal(type, quarter) ),
                        gsTotal = parseFloat( this.studentGSTotal(student, type, quarter) ),
                        psTotal = (0 == hpsTotal) ? 0 : (( gsTotal / hpsTotal ) * 100)

                    return psTotal
                }
            },
            studentWSTotal() {
                return (student, type, quarter) => {
                    var ws = parseFloat(this.vars.headers[type].percentage / 100),
                        psTotal = this.studentPSTotal(student, type, quarter),
                        wsTotal = (0 == psTotal) ? 0 : ( psTotal * ws )

                    return wsTotal
                }
            },
            studentInitialGrade() {
                return (student, quarter) => {
                    var ww = this.studentWSTotal(student, 'written_work', quarter),
                        pt = this.studentWSTotal(student, 'performance_task', quarter),
                        qa = this.studentWSTotal(student, 'quarterly_assessment', quarter)

                    return (ww + pt + qa)
                }
            },
            studentQuarterlyGrade() {
                return (student, quarter) => {
                    var tg = JSON.parse( this.settings.transmuted_grades ),
                        ig = parseFloat( this.studentInitialGrade(student, quarter) )

                    return (0 == ig) ? {grade: 0} : tg.find(t => {
                        return (t.from >= ig) && (ig <= t.to)
                    })
                }
            },
            studentFinalsGrade() {
                return (student) => {
                    var first = this.studentQuarterlyGrade(student, 'first').grade,
                        second = this.studentQuarterlyGrade(student, 'second').grade

                    return ((first + second) / 2)
                }
            }
        },
        methods     : {
            exportGrades() { 
                var wb = XLSX.utils.book_new() // make Workbook of Excel

                this.consolidated_grades.grading_sheets.forEach((gradingSheet, gradingSheetIndex) => {
                    let columns = []

                    gradingSheet.grades.forEach((grade, gradeIndex) => {
                        columns.push({
                            'Student'           : grade.student_id.name,
                            'First Quarter'     : this.studentQuarterlyGrade(gradeIndex, 'first').grade,
                            'Second Quarter'    : this.studentQuarterlyGrade(gradeIndex, 'second').grade,
                            'Final Grade'       : this.studentFinalsGrade(gradeIndex)
                        })
                    })

                    XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(columns), gradingSheet.subject_id.name)
                })
                // add Worksheet to Workbook

                // export Excel file
                XLSX.writeFile(wb, `${this.consolidated_grades.name} Consolidated Grades.xlsx`) // name of the file is 'book.xlsx'
            }
        },
        created() {
            this.$store.dispatch('getDataBySource', { source: 'sections', id: this.$route.params.id, no_commit: true }).then(response => {
                let sheets = response.data.response

                if( sheets.grading_sheets && 0 < sheets.grading_sheets.length ) {
                    sheets.grading_sheets = sheets.grading_sheets.filter(gs => this.$route.params.semester == gs.semester)
                }

                this.consolidated_grades = sheets
                this.vars.headers.written_work.percentage = sheets.grading_sheets[0].subject_track_id.written_work
                this.vars.headers.performance_task.percentage = sheets.grading_sheets[0].subject_track_id.performance_task
                this.vars.headers.quarterly_assessment.percentage = sheets.grading_sheets[0].subject_track_id.quarterly_assessment
            })
        }
    }
</script>