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
                                    <vs-td>{{ quarterGrade(grading_sheet.grades[indextr], indexg, 'first') }}%</vs-td>
                                    <vs-td>{{ quarterGrade(grading_sheet.grades[indextr], indexg, 'second') }}%</vs-td>
                                    <vs-td>{{ finalsGrade(grading_sheet.grades[indextr], indexg) }}%</vs-td>
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
                ]
            }
        },
        methods     : {
            quarterGrade(grades, index, quarter = 'first') {
                let quarterGrade = 0

                if( 0 < this.consolidated_grades.grading_sheets.length ) {
                    this.tracks.forEach((v, k) => {
                        let percentage = (this.consolidated_grades.grading_sheets[index].subject_track_id[v.key] / 100),
                            grade = (grades[v.key].quarters[quarter].reduce((a, b) => { return parseFloat(a) + parseFloat(b) }) / grades[v.key].quarters[quarter].filter(q => { return q }).length)

                        quarterGrade += (grade * percentage)
                    })
                }

                return isNaN(quarterGrade) ? 0 : quarterGrade.toFixed(2)
            },
            finalsGrade(grades, index) {
                let finalsGrade = 0

                if( 0 < this.consolidated_grades.grading_sheets.length ) {
                    this.tracks.forEach((v, k) => {
                        let percentage = (this.consolidated_grades.grading_sheets[index].subject_track_id[v.key] / 100),
                            firstQGrade = ((grades[v.key].quarters.first.reduce((a, b) => parseFloat(a) + parseFloat(b)) / grades[v.key].quarters.first.filter(q => q != 0).length) * percentage),
                            secondQGrade = ((grades[v.key].quarters.second.reduce((a, b) => parseFloat(a) + parseFloat(b)) / grades[v.key].quarters.second.filter(q => q != 0).length) * percentage)

                        finalsGrade += (firstQGrade + secondQGrade) / 2
                    })
                }

                return isNaN(finalsGrade) ? 0 : finalsGrade.toFixed(2)
            },
            exportGrades() { 
                var wb = XLSX.utils.book_new() // make Workbook of Excel

                this.consolidated_grades.grading_sheets.forEach((gradingSheet, gradingSheetIndex) => {
                    let columns = []

                    gradingSheet.grades.forEach((grade, gradeIndex) => {
                        columns.push({
                            'Student'           : grade.student_id.name,
                            'First Quarter'     : this.quarterGrade(grade, gradingSheetIndex, 'first'),
                            'Second Quarter'    : this.quarterGrade(grade, gradingSheetIndex, 'second'),
                            'Final Grade'       : this.finalsGrade(grade, gradingSheetIndex)
                        })
                    })

                    XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(columns), gradingSheet.subject_id.name)
                })
                // add Worksheet to Workbook
                
                // XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(this.xlsx.second), '2nd') 
                // XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(this.xlsx.finals), 'Final Grades')  

                // export Excel file
                XLSX.writeFile(wb, `${this.consolidated_grades.name} Consolidated Grades.xlsx`) // name of the file is 'book.xlsx'
            }
        },
        created() {
            // this.$store.commit('SET_TEMPLATE_OPTION', { key: 'noSidebar', value: true })

            this.$store.dispatch('getDataBySource', { source: 'sections', id: this.$route.params.id, no_commit: true }).then(response => {
                let sheets = response.data.response

                if( sheets.grading_sheets && 0 < sheets.grading_sheets.length ) {
                    sheets.grading_sheets = sheets.grading_sheets.filter(gs => this.$route.params.semester == gs.semester)
                }

                this.consolidated_grades = sheets
            })
        }
    }
</script>