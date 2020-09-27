<template>
    <div>
        <vs-row>
            <vs-col vs-xs="12" vs-sm="12" vs-lg="8">
                <h3>{{ $route.params.id ? 'Update' : 'New' }} Grading Sheet</h3>
                <router-link :to="{ name: 'grading-sheets' }">
                    <small>&laquo; Return to all grading sheets</small>
                </router-link>
            </vs-col>
            <vs-col vs-xs="12" vs-sm="12" vs-lg="4" class="text-right">
                <vs-button v-if="$route.params.id" @click="exportSheet" color="dark" class="mr-2">
                    <vs-icon icon="import_export" size="16px"></vs-icon> Export
                </vs-button>
                <vs-button v-if="isRole('teacher')" @click="$route.params.id ? update() : create()" :disabled="(0 == settings.status)">Save</vs-button>
            </vs-col>
        </vs-row>

        <vs-row class="mt-3">
            <vs-col vs-xs="6" vs-sm="8" vs-lg="9"></vs-col>
            <vs-col vs-xs="6" vs-sm="4" vs-lg="3">
                <div v-if="isRole('chief-adviser') || isRole('program-head')">
                    <div v-if="(isRole('chief-adviser') && heads.chief_adviser.approval) || (isRole('program-head') && heads.program_head.approval)">
                        <h5 class="mb-2">Grading Sheet Approval</h5>
                        <vs-switch @input="updateApproval" v-model="models.approval.status">
                            <span slot="on">Approved</span>
                            <span slot="off">Unapproved</span>
                        </vs-switch>
                    </div>
                </div>
                <div v-else>
                    <vs-select v-model="models.approval.user_id" label="Request approval to" placeholder="Select Approval Requestee">
                        <vs-select-item :value="heads.chief_adviser.id" :text="heads.chief_adviser.name"></vs-select-item>
                        <vs-select-item :value="heads.program_head.id" :text="heads.program_head.name"></vs-select-item>
                    </vs-select>
                    <vs-button @click="requestApproval" :disabled="!models.approval.user_id" class="mt-2 float-right">Request Approval</vs-button>
                </div>
            </vs-col>
        </vs-row>

        <vs-row class="mt-4">
            <vs-col vs-xs="12" vs-sm="6" vs-lg="3">
               
            </vs-col>
            <vs-col vs-xs="12" vs-sm="6" vs-lg="3">
                <div v-if="'teacher' == user.role">
                    <vs-select v-model="models.grading_sheet.level_id" label="Grade Level" placeholder="Select Grade Level" class="mb-2">
                        <vs-select-item v-for="(level, index) in options.levels" :key="index" :value="level.value" :text="level.text"></vs-select-item>
                    </vs-select>
                    <p v-if="0 == options.levels.length" class="mb-3">
                        No grade levels found. Please create <router-link :to="{ name: 'level_new' }">here.</router-link>
                    </p>
                
                    <vs-select v-model="models.grading_sheet.section_id" @change="getSectionStudents" label="Section" placeholder="Select Section" class="mb-2">
                        <vs-select-item v-for="(section, index) in options.sections" :key="index" :value="section.value" :text="section.text"></vs-select-item>
                    </vs-select>
                    <p v-if="0 == options.sections.length" class="mb-3">
                        No sections found. Please create <router-link :to="{ name: 'section_new' }">here.</router-link>
                    </p>
                </div>
                <div v-else>
                    <p class="mb-2">
                        <strong>Grade Level :</strong> {{ models.grading_sheet.sheet ? models.grading_sheet.sheet.level_id.name : '' }}
                    </p>
                    <p>
                        <strong>Section :</strong> {{ models.grading_sheet.sheet ? models.grading_sheet.sheet.section_id.name : '' }}
                    </p>
                </div>
            </vs-col>
            <vs-col vs-xs="12" vs-sm="6" vs-lg="3">
                <div v-if="'teacher' == user.role">
                    <vs-select v-model="models.grading_sheet.user_id" label="Adviser" placeholder="Select Adviser" class="mb-2">
                        <vs-select-item v-for="(adviser, index) in options.advisers" :key="index" :value="adviser.value" :text="adviser.text"></vs-select-item>
                    </vs-select>
                    <vs-select v-model="models.grading_sheet.semester" label="Semester" placeholder="Select Semester" class="mb-2">
                        <vs-select-item value="first" text="First Semester"></vs-select-item>
                        <vs-select-item value="second" text="Second Semester"></vs-select-item>
                    </vs-select>
                </div>
                <div v-else>
                    <p class="mb-2">
                        <strong>Adviser :</strong> {{ models.grading_sheet.sheet ? models.grading_sheet.sheet.user_id.name : '' }}
                    </p>
                    <p>
                        <strong>Semester :</strong> {{ models.grading_sheet.sheet ? models.grading_sheet.sheet.semester : '' }}
                    </p>
                </div>
            </vs-col>
            <vs-col vs-xs="12" vs-sm="6" vs-lg="3">
                <div v-if="'teacher' == user.role">
                    <vs-select v-model="models.grading_sheet.subject_id" @change="getSubjectTracks" label="Subject" placeholder="Select Subject" class="mb-2">
                        <vs-select-item v-for="(subject, index) in options.subjects" :key="index" :value="subject.value" :text="subject.text"></vs-select-item>
                    </vs-select>
                    <p v-if="0 == options.subjects.length" class="mb-3">
                        No subjects found. Please create <router-link :to="{ name: 'subject_new' }">here.</router-link>
                    </p>

                    <vs-select v-model="models.grading_sheet.subject_track_id" @change="getSubjectTrackCols" label="Subject Track" placeholder="Select Track" class="mb-2">
                        <vs-select-item v-for="(subject_track, index) in options.subject_tracks" :key="index" :value="subject_track.value" :text="subject_track.text"></vs-select-item>
                    </vs-select>
                    <p v-if="0 == options.subject_tracks.length" class="mb-3">
                        No subject tracks found. Please create <router-link :to="{ name: 'subject-track_new' }">here.</router-link>
                    </p>
                </div>
                <div v-else>
                    <p class="mb-2">
                        <strong>Subject :</strong> {{ models.grading_sheet.sheet ? models.grading_sheet.sheet.subject_id.name : '' }}
                    </p>
                    <p>
                        <strong>Subject Track :</strong> {{ models.grading_sheet.sheet ? models.grading_sheet.sheet.subject_track_id.name : '' }}
                    </p>
                </div>
            </vs-col>
        </vs-row>

        <vs-tabs>
            <vs-tab v-for="(quarter, indexq) in vars.quarters" :key="indexq" :label="quarter">
                <vs-card>
                    <div>
                        <vs-table :data="models.grading_sheet.grades">
                            <template slot="thead">
                                <vs-th width="350">Learner’s Name</vs-th>
                                <vs-th v-for="(vh, indexvh) in vars.headers" :key="indexvh">
                                    <vs-row>
                                        <vs-col vs-xs="12" vs-sm="12" vs-lg="12" class="text-center">
                                            <h4 class="mb-1">{{ vh.title }} ({{ vh.percentage }}%)</h4>
                                            <vs-button @click="editHPS(indexq)" color="primary" type="flat" class="p-0 mb-3">Edit Highest Possible Scores</vs-button>
                                        </vs-col>
                                        <vs-col vs-xs="4" vs-sm="4" vs-lg="4" class="text-center">Total</vs-col>
                                        <vs-col vs-xs="4" vs-sm="4" vs-lg="4" class="text-center">PS</vs-col>
                                        <vs-col vs-xs="4" vs-sm="4" vs-lg="4" class="text-center">WS</vs-col>
                                    </vs-row>
                                </vs-th>
                                <vs-th width="150">Initial Grades</vs-th>
                                <vs-th width="150">Quarterly Grades</vs-th>
                            </template>
                            <template slot-scope="{data}">
                                <vs-tr v-for="(tr, indextr) in models.grading_sheet.grades" :key="indextr">
                                    <vs-td>
                                        <strong>{{ tr.student_id.student_id }}</strong>
                                        <br>
                                        {{ tr.student_id.name }}
                                        <vs-button @click="editStudentRatings(tr, indexq, indextr)" color="primary" type="flat" class="p-0 mb-3 float-right">{{  isRole('teacher') ? 'Edit' : 'View' }} Grades</vs-button>
                                    </vs-td>
                                    <vs-td v-for="(vh, indexvh) in vars.headers" :key="indexvh">
                                        <vs-row>
                                            <vs-col vs-xs="4" vs-sm="4" vs-lg="4" class="text-center">{{ studentGSTotal(indextr, indexvh, indexq) }}</vs-col>
                                            <vs-col vs-xs="4" vs-sm="4" vs-lg="4" class="text-center">{{ studentPSTotal(indextr, indexvh, indexq) }}</vs-col>
                                            <vs-col vs-xs="4" vs-sm="4" vs-lg="4" class="text-center">{{ `${studentWSTotal(indextr, indexvh, indexq).toFixed(2)}%` }}</vs-col>
                                        </vs-row>
                                    </vs-td>
                                    <vs-td>
                                        {{ studentInitialGrade(indextr, indexq).toFixed(2) }}
                                    </vs-td>
                                    <vs-td>
                                        {{ studentQuarterlyGrade(indextr, indexq).grade }}
                                    </vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </vs-card>
            </vs-tab>
        </vs-tabs>

        <vs-popup v-if="vars.popup.hps" :active.sync="vars.popup.hps" title="Update Highest Possible Score">
            <vs-tabs>
                <vs-tab v-for="(header, indexh) in vars.headers" :key="indexh" :label="header.title">
                    <div>
                        <vs-list>
                            <vs-list-item v-for="(quarter, indexq) in models.grading_sheet.grades[vars.popup.gradeIndex][indexh].quarters[vars.popup.quarter]" :key="indexq" :subtitle="`Q${(indexq + 1)}`">
                                <div v-if="isRole('teacher')" class="text-center">
                                    <vs-input-number v-model="models.grading_sheet.hps[indexh][vars.popup.quarter][indexq]" :step="0.1" :min="0" :max="100" :tabindex="200 + indexq" class="mb-0"/>
                                </div>
                                <span v-else>{{ models.grading_sheet.hps[indexh][vars.popup.quarter] }}</span>
                            </vs-list-item>
                            <vs-list-item subtitle="Total">
                                {{ hpsTotal(indexh, vars.popup.quarter) }}
                            </vs-list-item>
                        </vs-list>
                    </div>
                </vs-tab>
            </vs-tabs>
        </vs-popup>

        <vs-popup v-if="vars.popup.active" :active.sync="vars.popup.active" title="Update Grading Sheet">
            <vs-row>
                <vs-col vs-xs="12" vs-sm="12" vs-lg="7">
                    <p>{{ vars.popup.grade.student_id.student_id }}</p>
                    <h4 class="mt-1">{{ vars.popup.grade.student_id.name }}</h4>
                </vs-col>
                <vs-col vs-xs="12" vs-sm="12" vs-lg="5">
                    <vs-select v-model="vars.popup.grade" @change="editStudentRatings(vars.popup.grade, vars.popup.quarter, models.grading_sheet.grades.indexOf(vars.popup.grade))" label="Select Student">
                        <vs-select-item v-for="(grade, indexg) in models.grading_sheet.grades" :key="indexg" :value="grade" :text="grade.student_id.name"></vs-select-item>
                    </vs-select>
                </vs-col>
            </vs-row>
            <vs-divider class="mb-2"></vs-divider>
            <vs-tabs>
                <vs-tab v-for="(header, indexh) in vars.headers" :key="indexh" :label="header.title">
                    <div>
                        <vs-list>
                            <vs-list-item v-for="(quarter, indexq) in models.grading_sheet.grades[vars.popup.gradeIndex][indexh].quarters[vars.popup.quarter]" :key="indexq" :subtitle="`Q${(indexq + 1)}`">
                                <div v-if="isRole('teacher')" class="text-center">
                                    <vs-input-number v-model="models.grading_sheet.grades[vars.popup.gradeIndex][indexh].quarters[vars.popup.quarter][indexq]" :step="0.1" :min="0" :max="models.grading_sheet.hps[indexh][vars.popup.quarter][indexq]" :disabled="!models.grading_sheet.hps[indexh][vars.popup.quarter][indexq]" :tabindex="200 + indexq" class="mb-0"/>
                                    <small>HPS: {{ models.grading_sheet.hps[indexh][vars.popup.quarter][indexq] }}</small>
                                </div>
                                <span v-else>{{ models.grading_sheet.grades[vars.popup.gradeIndex][indexh].quarters[vars.popup.quarter][indexq] }}%</span>
                            </vs-list-item>
                        </vs-list>
                    </div>
                </vs-tab>
            </vs-tabs>
        </vs-popup>

        <vs-row class="mt-4 mb-5 text-center">
            <vs-col vs-xs="12" vs-sm="4" vs-lg="4">
                <!-- <h4>{{ $route.params.id ? models.grading_sheet.sheet.user_id.name : '' }}</h4> -->
                <small>Subject Teacher</small>
            </vs-col>
            <vs-col vs-xs="12" vs-sm="4" vs-lg="4">
                <vs-chip :color="heads.chief_adviser.approval ? ( heads.chief_adviser.approval.status ? 'primary' : 'dark' ) : 'dark'" class="mb-2 float-none">{{ heads.chief_adviser.approval ? ( heads.chief_adviser.approval.status ? 'Approved' : 'Pending Approval' ) : 'Unapproved' }}</vs-chip>
                <h4>{{ heads.chief_adviser.name }}</h4>
                <small>Chief Adviser</small>
            </vs-col>
            <vs-col vs-xs="12" vs-sm="4" vs-lg="4">
                <vs-chip :color="heads.program_head.approval ? ( heads.program_head.approval.status ? 'primary' : 'dark' ) : 'dark'" class="mb-2 float-none">{{ heads.program_head.approval ? ( heads.program_head.approval.status ? 'Approved' : 'Pending Approval' ) : 'Unapproved' }}</vs-chip>
                <h4>{{ heads.program_head.name }}</h4>
                <small>Program Head</small>
            </vs-col>
        </vs-row>

        <comments :grading_sheet="$route.params.id" :commenter="user.id"></comments>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import XLSX from 'xlsx'
    import Comments from 'Views/portal/views/record-management/grading-sheets/components/Comments.vue'

    export default {
        components      : {
            'comments'      : Comments
        },
        data() {
            return {
                models  : {
                    grading_sheet    : {
                        grades              : [],
                        hps                 : {
                            written_work            : {
                                first                   : [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                                second                  : [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ]
                            },
                            performance_task        : {
                                first                   : [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                                second                  : [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ]
                            },
                            quarterly_assessment    : {
                                first                   : [ 0 ],
                                second                  : [ 0 ]
                            }
                        },
                        section_id          : this.$route.params.section ? this.$route.params.section : null,
                        level_id            : this.$route.params.level ? this.$route.params.level : null,
                        user_id             : this.$route.params.adviser ? this.$route.params.adviser : null,
                        semester            : this.$route.params.semester ? this.$route.params.semester : 'first'
                    },
                    approval        : {
                        grading_sheet_id    : this.$route.params.id ? this.$route.params.id : 0,
                        user_id             : 0,
                        status              : false
                    }
                },
                vars    : {
                    quarters    : {
                        first       : 'First Quarter',
                        second      : 'Second Quarter'  
                    },
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
                    },
                    popup       : {
                        active          : false,
                        hps             : false,
                        grade           : null,
                        quarter         : 'first',
                        gradeIndex      : 0
                    }
                },
                heads       : {
                    chief_adviser   : { name: '' },
                    program_head    : { name: '' }
                }
            }
        },
        methods     : {
            editHPS(quarter) {
                this.vars.popup.hps = true
                this.vars.popup.quarter = quarter
            },
            editStudentRatings(grade, quarter, index) {
                this.vars.popup.active = true
                this.vars.popup.grade = grade
                this.vars.popup.quarter = quarter
                this.vars.popup.gradeIndex = index
            },
            getSectionStudents(sectionId = false) {
                sectionId = !sectionId ? this.models.grading_sheet.section_id.id : sectionId

                this.$store.dispatch('getDataBySource', { source: 'sections', id: sectionId, no_commit: true }).then(response => {
                    // this.models.grading_sheet.students = response.data.response.students
                })
            },
            getSubjectTracks(subjectId = false) {
                subjectId = !subjectId ? this.models.grading_sheet.subject_id.id : subjectId

                this.$store.dispatch('getDataBySource', { source: 'subjects', id: subjectId, no_commit: true }).then(response => {
                    this.models.grading_sheet.subject_track_id = response.data.response.subject_track_id
                })
            },
            getSubjectTrackCols(subjectTrackId = false) {
                subjectTrackId = !subjectTrackId ? this.models.grading_sheet.subject_track_id : subjectTrackId

                this.$store.dispatch('getDataBySource', { source: 'subject_tracks', id: subjectTrackId, no_commit: true }).then(response => {
                    this.vars.headers.written_work.percentage = response.data.response.written_work
                    this.vars.headers.performance_task.percentage = response.data.response.performance_task
                    this.vars.headers.quarterly_assessment.percentage = response.data.response.quarterly_assessment
                })
            },
            create() {
                this.$store.dispatch('createDataBySource', { source: 'grading_sheets', data: this.models.grading_sheet }).then(response => {
                    this.$vs.notify({ title: 'Success', text: 'New grading sheet created.', color: 'success' })

                    this.$router.push({ name: 'grading-sheet_edit', params: { id: response.data.response } })
                })
            },
            update() {
                this.$store.dispatch('updateDataBySource', { source: 'grading_sheets', id: this.models.grading_sheet.id, data: this.models.grading_sheet }).then(response => {
                    this.$vs.notify({ title: 'Success', text: 'Grading sheet updated.', color: 'success' })
                })
            },
            exportSheet() {
                var quarters = ['first', 'second'],
                    wb = XLSX.utils.book_new(),
                    data = {
                        first   : [
                            // Header
                            [ ``, ``, ``, `Senior High School Class Record`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, `Region: ${this.settings.region}`, ``, ``, ``, ``, `Division: ${this.settings.division}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, `School Name: ${this.settings.school_name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `School ID: ${this.settings.school_id}`, ``, ``, ``, ``, `School Year: `, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            // Quarter
                            [ `First Quarter`, ``, ``, `Grade: ${this.models.grading_sheet.sheet.level_id.name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Teacher: ${this.models.grading_sheet.sheet.user_id.name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Subject: ${this.models.grading_sheet.sheet.subject_id.name}`, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, `Section: ${this.models.grading_sheet.sheet.section_id.name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``,``,  `Semester: ${this.models.grading_sheet.sheet.semester}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Track: ${this.models.grading_sheet.sheet.subject_track_id.name}`, ``, ``, ``, ``, ``, ``, ``, `` ],
                            // Learner's Name
                            [ `Learner’s Name`, ``, ``, `Written Work (${this.vars.headers.written_work.percentage}%)`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Performance Task (${this.vars.headers.performance_task.percentage}%)`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Quarterly Assessment (${this.vars.headers.quarterly_assessment.percentage}%)`, ``, ``, ``, `Initial Grade`, `Quarterly Grade` ],
                            [ ``, ``, ``, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, `Total`, `PS`, `WS`, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, `Total`, `PS`, `WS`, 1, `Total`, `PS`, `WS`, ``, `` ],
                            // Highest Possible Score
                            [ `Highest Possible Score`, ``, ``, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ``, 100, `${this.vars.headers.written_work.percentage}%`, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ``, 100, `${this.vars.headers.performance_task.percentage}%`, 0, ``, 100, `${this.vars.headers.quarterly_assessment.percentage}%`, ``, `` ],
                        ],
                        second  : [
                            // Header
                            [ ``, ``, ``, `Senior High School Class Record`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, `Region: ${this.settings.region}`, ``, ``, ``, ``, `Division: ${this.settings.division}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, `School Name: ${this.settings.school_name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `School ID: ${this.settings.school_id}`, ``, ``, ``, ``, `School Year: `, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            // Quarter
                            [ `Second Quarter`, ``, ``, `Grade: ${this.models.grading_sheet.sheet.level_id.name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Teacher: ${this.models.grading_sheet.sheet.user_id.name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Subject: ${this.models.grading_sheet.sheet.subject_id.name}`, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, `Section: ${this.models.grading_sheet.sheet.section_id.name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``,``,  `Semester: ${this.models.grading_sheet.sheet.semester}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Track: ${this.models.grading_sheet.sheet.subject_track_id.name}`, ``, ``, ``, ``, ``, ``, ``, `` ],
                            // Learner's Name
                            [ `Learner’s Name`, ``, ``, `Written Work (${this.vars.headers.written_work.percentage}%)`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Performance Task (${this.vars.headers.performance_task.percentage}%)`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Quarterly Assessment (${this.vars.headers.quarterly_assessment.percentage}%)`, ``, ``, ``, `Initial Grade`, `Quarterly Grade` ],
                            [ ``, ``, ``, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, `Total`, `PS`, `WS`, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, `Total`, `PS`, `WS`, 1, `Total`, `PS`, `WS`, ``, `` ],
                            // Highest Possible Score
                            [ `Highest Possible Score`, ``, ``, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ``, 100, `${this.vars.headers.written_work.percentage}%`, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ``, 100, `${this.vars.headers.performance_task.percentage}%`, 0, ``, 100, `${this.vars.headers.quarterly_assessment.percentage}%`, ``, `` ],
                        ],
                        finals  : [
                            // Header
                            [ ``, ``, ``, `Senior High School Class Record`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, `Region: ${this.settings.region}`, ``, ``, ``, ``, `Division: ${this.settings.division}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, `School Name: ${this.settings.school_name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `School ID: ${this.settings.school_id}`, ``, ``, ``, ``, `School Year: `, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `` ],
                            // Quarter
                            [ ``, ``, ``, `Grade: ${this.models.grading_sheet.sheet.level_id.name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Teacher: ${this.models.grading_sheet.sheet.user_id.name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Subject: ${this.models.grading_sheet.sheet.subject_id.name}`, ``, ``, ``, ``, ``, ``, ``, `` ],
                            [ ``, ``, ``, `Section: ${this.models.grading_sheet.sheet.section_id.name}`, ``, ``, ``, ``, ``, ``, ``, ``, ``,``,  `Semester: ${this.models.grading_sheet.sheet.semester}`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Track: ${this.models.grading_sheet.sheet.subject_track_id.name}`, ``, ``, ``, ``, ``, ``, ``, `` ],
                            // Learner's Name
                            [ `Learner’s Name`, ``, ``, `First Quarter`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Second Quarter`, ``, ``, ``, ``, ``, ``, ``, ``, ``, `Final Grades`, ``, ``, ``, ``, `Remarks`, ``, ``, ``, `` ],
                        ]
                    }
                
                quarters.forEach(q => {
                    // Written work
                    this.models.grading_sheet.hps.written_work[q].forEach((v, k) => {
                        data[q][10][(3 + k)] = v
                    })
                    data[q][10][13] = this.hpsTotal('written_work', q)

                    // Performance task
                    this.models.grading_sheet.hps.performance_task[q].forEach((v, k) => {
                        data[q][10][(16 + k)] = v
                    })
                    data[q][10][26] = this.hpsTotal('performance_task', q)
                    
                    // Quarterly assessment
                    this.models.grading_sheet.hps.quarterly_assessment[q].forEach((v, k) => {
                        data[q][10][(29 + k)] = v
                    })
                    data[q][10][30] = this.hpsTotal('quarterly_assessment', q)

                    this.models.grading_sheet.grades.forEach((grade, grade_i) => {
                        let ww = grade.written_work.quarters[q],
                            pt = grade.performance_task.quarters[q],
                            qa = grade.quarterly_assessment.quarters[q]

                        data[q].push([
                            grade.student_id.name, ``, ``,
                            ww[0], ww[1], ww[2], ww[3], ww[4], ww[5], ww[6], ww[7], ww[8], ww[9], this.studentGSTotal(grade_i, 'written_work', q), this.studentPSTotal(grade_i, 'written_work', q), `${this.studentWSTotal(grade_i, 'written_work', q).toFixed(2)}%`,
                            pt[0], pt[1], pt[2],  pt[3], pt[4], pt[5], pt[6], pt[7], pt[8], pt[9], this.studentGSTotal(grade_i, 'performance_task', q), this.studentGSTotal(grade_i, 'performance_task', q), `${this.studentWSTotal(grade_i, 'performance_task', q).toFixed(2)}%`,
                            qa[0], this.studentGSTotal(grade_i, 'quarterly_assessment', q), this.studentPSTotal(grade_i, 'quarterly_assessment', q), `${this.studentWSTotal(grade_i, 'quarterly_assessment', q).toFixed(2)}%`,
                            this.studentInitialGrade(grade_i, q).toFixed(2), this.studentQuarterlyGrade(grade_i, q).grade
                        ])

                        data.finals.push([
                            grade.student_id.name, ``, ``,
                            `pers`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``,
                            `sikan`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``,
                            `paynals`, ``, ``, ``, ``,
                            ``, ``, ``, ``, ``
                        ])
                    })
                })
        
                var cols = [
                    { hidden: false, wpx: 70, width: 7.5, wch: 15 },
                    { hidden: false, wpx: 70, width: 7.5, wch: 15 },
                    { hidden: false, wpx: 70, width: 7.5, wch: 15 },

                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 50, width: 5, wch: 5 },
                    { hidden: false, wpx: 50, width: 5, wch: 5 },
                    { hidden: false, wpx: 50, width: 5, wch: 5 },
                    
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 35, width: 3.75, wch: 2 },
                    { hidden: false, wpx: 50, width: 5, wch: 5 },
                    { hidden: false, wpx: 50, width: 5, wch: 5 },
                    { hidden: false, wpx: 50, width: 5, wch: 5 },

                    { hidden: false, wpx: 50, width: 5, wch: 5 },
                    { hidden: false, wpx: 50, width: 5, wch: 5 },
                    { hidden: false, wpx: 50, width: 5, wch: 5 },

                    { hidden: false, wpx: 70, width: 7.5, wch: 15 },
                    { hidden: false, wpx: 70, width: 7.5, wch: 15 }
                ],
                quartersMerges = [
                    { s: { r: 0, c: 0 }, e: { r: 5, c: 2 } }, // Logo Left
                    { s: { r: 0, c: 31 }, e: { r: 5, c: 34 } }, // Logo Right
                    { s: { r: 0, c: 3 }, e: { r: 1, c: 30 } }, // Title																									
                    { s: { r: 2, c: 3 }, e: { r: 2, c: 30 } }, // Subtitle
                    { s: { r: 3, c: 3 }, e: { r: 3, c: 7 } }, // Region
                    { s: { r: 3, c: 8 }, e: { r: 3, c: 13 } }, // Division
                    { s: { r: 4, c: 3 }, e: { r: 4, c: 13 } }, // School Name
                    { s: { r: 4, c: 14 }, e: { r: 4, c: 18 } }, // School ID
                    { s: { r: 4, c: 19 }, e: { r: 4, c: 24 } }, // School Year
                    { s: { r: 5, c: 3 }, e: { r: 5, c: 30 } }, // Separator on row 6
                    { s: { r: 6, c: 0 }, e: { r: 7, c: 2 } }, // Quarter
                    { s: { r: 6, c: 3 }, e: { r: 6, c: 13 } }, // Grade
                    { s: { r: 6, c: 14 }, e: { r: 6, c: 24 } }, // Teacher
                    { s: { r: 6, c: 25 }, e: { r: 6, c: 34 } }, // Subject
                    { s: { r: 7, c: 3 }, e: { r: 7, c: 13 } }, // Section
                    { s: { r: 7, c: 14 }, e: { r: 7, c: 24 } }, // Semester
                    { s: { r: 7, c: 25 }, e: { r: 7, c: 33 } }, // Track
                    { s: { r: 8, c: 3 }, e: { r: 8, c: 15 } }, // Written Work
                    { s: { r: 8, c: 16 }, e: { r: 8, c: 28 } }, // Performance Task
                    { s: { r: 8, c: 29 }, e: { r: 8, c: 32 } }, // Quarterly Assessment
                    { s: { r: 8, c: 0 }, e: { r: 9, c: 2 } }, // Learner's Name
                    { s: { r: 10, c: 0 }, e: { r: 10, c: 2 } } // Highest Possible Score
                ],
                finalsMerges = [
                    { s: { r: 0, c: 0 }, e: { r: 5, c: 2 } }, // Logo Left
                    { s: { r: 0, c: 31 }, e: { r: 5, c: 34 } }, // Logo Right
                    { s: { r: 0, c: 3 }, e: { r: 1, c: 30 } }, // Title																									
                    { s: { r: 2, c: 3 }, e: { r: 2, c: 30 } }, // Subtitle
                    { s: { r: 3, c: 3 }, e: { r: 3, c: 7 } }, // Region
                    { s: { r: 3, c: 8 }, e: { r: 3, c: 13 } }, // Division
                    { s: { r: 4, c: 3 }, e: { r: 4, c: 13 } }, // School Name
                    { s: { r: 4, c: 14 }, e: { r: 4, c: 18 } }, // School ID
                    { s: { r: 4, c: 19 }, e: { r: 4, c: 24 } }, // School Year
                    { s: { r: 5, c: 3 }, e: { r: 5, c: 30 } }, // Separator on row 6
                    { s: { r: 6, c: 0 }, e: { r: 7, c: 2 } }, // Quarter
                    { s: { r: 6, c: 3 }, e: { r: 6, c: 13 } }, // Grade
                    { s: { r: 6, c: 14 }, e: { r: 6, c: 24 } }, // Teacher
                    { s: { r: 6, c: 25 }, e: { r: 6, c: 34 } }, // Subject
                    { s: { r: 7, c: 3 }, e: { r: 7, c: 13 } }, // Section
                    { s: { r: 7, c: 14 }, e: { r: 7, c: 24 } }, // Semester
                    { s: { r: 7, c: 25 }, e: { r: 7, c: 34 } }, // Track
                    { s: { r: 8, c: 0 }, e: { r: 8, c: 2 } }, // Learner's Name
                    { s: { r: 8, c: 3 }, e: { r: 8, c: 13 } }, // First Quarter
                    { s: { r: 8, c: 14 }, e: { r: 8, c: 24 } }, // Second Quarter
                    { s: { r: 8, c: 25 }, e: { r: 8, c: 29 } }, // Final Grades
                    { s: { r: 8, c: 30 }, e: { r: 8, c: 34 } } // Remarks
                ],
                ws = {
                    first   : XLSX.utils.aoa_to_sheet(data.first),
                    second  : XLSX.utils.aoa_to_sheet(data.second),
                    finals  : XLSX.utils.aoa_to_sheet(data.finals)
                }

                quarters.forEach(q => {
                    var qr = 10,
                        fr = 7
                    this.models.grading_sheet.grades.forEach(grade => {
                        qr++
                        fr++

                        quartersMerges.push({
                            s: { r: qr, c: 0 },
                            e: { r: qr, c: 2 }
                        })

                        finalsMerges.push({
                            s: { r: fr, c: 0 },
                            e: { r: fr, c: 2 }
                        })
                    })
                })

                ws.first["!merges"] = quartersMerges
                ws.first["!cols"] = cols

                ws.second["!merges"] = quartersMerges
                ws.second["!cols"] = cols

                ws.finals["!merges"] = finalsMerges
                ws.finals["!cols"] = cols
                
                // export Excel file
                XLSX.utils.book_append_sheet(wb, ws.first, 'First Quarter')
                XLSX.utils.book_append_sheet(wb, ws.second, 'Second Quarter')
                XLSX.utils.book_append_sheet(wb, ws.finals, 'Final Semester Grade')
                XLSX.writeFile(wb, `Grading Sheet.xlsx`) // name of the file is 'book.xlsx'
            },
            requestApproval() {
                if( this.models.grading_sheet.approvals.find(approval => approval.user_id == this.models.approval.user_id) )
                    this.$vs.notify({ title: 'Notice', text: 'An approval request has been sent to this user.', color: 'warning' })
                else 
                    this.$store.dispatch('createDataBySource', { source: 'grading_sheet_approvals', data: this.models.approval }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Approval request sent.', color: 'success' })
                    })
            },
            updateApproval() {
                this.$store.dispatch('updateDataBySource', { source: 'grading_sheet_approvals', id: this.models.approval.id, data: this.models.approval }).then(response => {
                    this.$vs.notify({ title: 'Success', text: 'Approval request updated.', color: 'success' })

                    if( 'chief-adviser' == this.user.role && this.heads.chief_adviser.approval ) {
                        this.heads.chief_adviser.approval.status = this.models.approval.status
                    } else if( 'program-head' == this.user.role && this.heads.program_head.approval ) {
                        this.heads.program_head.approval.status = this.models.approval.status
                    }
                })
            }
        },
        computed    : {
            ...mapGetters([
                'user', 'options', 'settings', 'isRole'
            ]),
            hpsTotal() {
                return (type, quarter) => {
                    var hps = this.models.grading_sheet.hps[type][quarter]

                    return hps.reduce((total, num) => {
                        return parseFloat(total) + parseFloat(num)
                    })
                }
            },
            studentGSTotal() {
                return (student, type, quarter) => {
                    var gs = this.models.grading_sheet.grades[student][type].quarters[quarter]

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
            }
        },
        created() {
            if( 'chief-adviser' == this.user.role || 'program-head' == this.user.role ) {
                this.models.approval.user_id = this.user.id
            }

            this.$store.dispatch('getSelectOptions', { source: 'levels', status: 'published' })
            this.$store.dispatch('getSelectOptions', { source: 'sections', status: 'published' })
            this.$store.dispatch('getSelectOptions', { source: 'subjects', status: 'published' })
            this.$store.dispatch('getSelectOptions', { source: 'subject_tracks', status: 'published' })
            this.$store.dispatch('getSelectOptions', { source: 'users', alias: 'advisers', filters: [{ key: 'role', value: 'teacher' }] })
            this.$store.dispatch('getUsers', { no_commit: true }).then(response => {
                if( 0 < response.data.response.length ) {
                    response.data.response.forEach(user => {
                        this.heads.chief_adviser = 'chief-adviser' == user.role ? user : this.heads.chief_adviser
                        this.heads.program_head = 'program-head' == user.role ? user : this.heads.program_head
                    })
                }
            })

            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'grading_sheets', id: this.$route.params.id }).then(response => {
                        this.models.grading_sheet = response.data.response
                        this.getSubjectTrackCols(response.data.response.subject_track_id)
                        
                        if( 'undefined' != typeof this.models.grading_sheet.approvals ) {
                            this.heads.chief_adviser.approval = this.models.grading_sheet.approvals.find(approval => approval.user_id == this.heads.chief_adviser.id)
                            this.heads.program_head.approval = this.models.grading_sheet.approvals.find(approval => approval.user_id == this.heads.program_head.id)
                            
                            if( this.isRole('chief-adviser') && this.heads.chief_adviser.approval ) {
                                this.models.approval.id = this.heads.chief_adviser.approval.id
                                this.models.approval.status = this.heads.chief_adviser.approval.status
                            } else if( this.isRole('program-head') && this.heads.program_head.approval ) {
                                this.models.approval.id = this.heads.program_head.approval.id
                                this.models.approval.status =  this.heads.program_head.approval.status
                            }
                        }
                    }).catch(_ => {
                        this.$router.push({ name: 'grading-sheets' })
                    })
                } else {
                    this.models.grading_sheet = this.$store.state.apiData.active
                    this.getSubjectTrackCols(this.$store.state.apiData.active.subject_track_id)

                    if( 'undefined' != typeof this.models.grading_sheet.approvals ) {
                        this.heads.chief_adviser.approval = this.models.grading_sheet.approvals.find(approval => approval.user_id == this.heads.chief_adviser.id)
                        this.heads.program_head.approval = this.models.grading_sheet.approvals.find(approval => approval.user_id == this.heads.program_head.id)
                        
                        if( this.isRole('chief-adviser') && this.heads.chief_adviser.approval ) {
                            this.models.approval.id = this.heads.chief_adviser.approval.id
                            this.models.approval.status = this.heads.chief_adviser.approval.status
                        } else if( this.isRole('program-head') && this.heads.program_head.approval ) {
                            this.models.approval.id = this.heads.program_head.approval.id
                            this.models.approval.status =  this.heads.program_head.approval.status
                        }
                    }
                }
            } else {
                this.$store.dispatch('getDatasBySource', { source: 'students', status: 'published', no_commit: true }).then(response => {
                    response.data.response.forEach(student => {
                        this.models.grading_sheet.grades.push({
                            grading_sheet_id        : null,
                            student_id              : student,
                            written_work            : {
                                quarters                : {
                                    first                   : [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                                    second                  : [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ]
                                },
                                totals                  : {
                                    first                   : 0,
                                    second                  : 0
                                }
                            },
                            performance_task        : {
                                quarters                : {
                                    first                   : [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
                                    second                  : [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ]
                                },
                                totals                  : {
                                    first                   : 0,
                                    second                  : 0
                                }
                            },
                            quarterly_assessment    : {
                                quarters                : {
                                    first                   : [ 0 ],
                                    second                  : [ 0 ]
                                },
                                totals                  : {
                                    first                   : 0,
                                    second                  : 0
                                }
                            }
                        })
                    })
                })
            }
        }
    }
</script>