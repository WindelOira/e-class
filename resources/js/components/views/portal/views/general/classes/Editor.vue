<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="my-1 float-left">{{ $route.params.id ? 'Update' : 'New' }} Class</h3>
                <vs-button v-if="$route.params.id" @click="$router.push({ name: 'class_new' })" class="float-right">Add New</vs-button>
            </vs-col>
        </vs-row>

        <validation-observer ref="observer">
            <form @submit.prevent="$route.params.id ? update() : create()">
                <vs-row>
                    <vs-col vs-xs="12" vs-sm="7" vs-lg="9">
                        <vs-card>
                            <p class="mb-3">
                                <router-link :to="{ name: 'classes' }">
                                    <small>&laquo; Return to all classes</small>
                                </router-link>
                            </p>

                            <vs-row>
                                <vs-col vs-xs="12" vs-sm="4" vs-lg="3">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-select v-model="models.class.semester" @change="getSubjectsBySemester" :danger="0 < errors.length" :danger-text="errors[0]" label="Semester" placeholder="Select Semester" class="mb-3">
                                            <vs-select-item v-for="(sem, index) in $store.state.options.semesters" :key="index" :value="sem.value" :text="sem.text"></vs-select-item>
                                        </vs-select>
                                    </validation-provider>
                                </vs-col>
                                <vs-col vs-xs="12" vs-sm="" vs-lg="6"></vs-col>
                                <vs-col vs-xs="12" vs-sm="4" vs-lg="3">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-select v-model="models.class.academic_year_id" @change="getSectionsByAcademicYear" :danger="0 < errors.length" :danger-text="errors[0]" label="Academic Year" placeholder="Select Academic Year" class="mb-2">
                                            <vs-select-item v-for="(academic_year, index) in $store.state.options.academic_years" :key="index" :value="academic_year.value" :text="academic_year.text"></vs-select-item>
                                        </vs-select>
                                    </validation-provider>
                                    <p v-if="0 == $store.state.options.academic_years.length" class="mb-3">
                                        No academic years found. Please create <router-link :to="{ name: 'academic-year_new' }">here.</router-link>
                                    </p>
                                </vs-col>
                            </vs-row>
                            <vs-row>
                                <vs-col vs-xs="12" vs-sm="7" vs-lg="8">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.class.name" :danger="0 < errors.length" :danger-text="errors[0]" label="Class Name" class="mb-3"></vs-input>
                                    </validation-provider>
                                </vs-col>
                                <vs-col vs-xs="12" vs-sm="5" vs-lg="4">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.class.hours" :danger="0 < errors.length" :danger-text="errors[0]" label="Hours" class="mb-3"></vs-input>
                                    </validation-provider>
                                </vs-col>
                            </vs-row>
                        </vs-card>
                    </vs-col>
                    <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                        <vs-card>
                            <div>
                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-select v-model="models.class.subject_id" :danger="0 < errors.length" :danger-text="errors[0]" label="Subject" placeholder="Select Subject" class="mb-2">
                                        <vs-select-item v-for="(subject, index) in $store.state.options.subjects" :key="index" :value="subject.value" :text="subject.text"></vs-select-item>
                                    </vs-select>
                                </validation-provider>
                                <p v-if="0 == $store.state.options.subjects.length" class="mb-3">
                                    No subjects found. Please create <router-link :to="{ name: 'subject_new' }">here.</router-link>
                                </p>

                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-select v-model="models.class.level_id" :danger="0 < errors.length" :danger-text="errors[0]" label="Grade" placeholder="Select Grade" class="mb-2">
                                        <vs-select-item v-for="(level, index) in $store.state.options.levels" :key="index" :value="level.value" :text="level.text"></vs-select-item>
                                    </vs-select>
                                </validation-provider>
                                <p v-if="0 == $store.state.options.levels.length" class="mb-3">
                                    No tracks found. Please create <router-link :to="{ name: 'level_new' }">here.</router-link>
                                </p>

                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-select v-model="models.class.section_id" :danger="0 < errors.length" :danger-text="errors[0]" label="Section" placeholder="Select Section" class="mb-2">
                                        <vs-select-item v-for="(section, index) in $store.state.options.sections" :key="index" :value="section.value" :text="section.text"></vs-select-item>
                                    </vs-select>
                                </validation-provider>
                                <p v-if="0 == $store.state.options.sections.length" class="mb-3">
                                    No sections found. Please create <router-link :to="{ name: 'section_new' }">here.</router-link>
                                </p>

                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-select v-model="models.class.strand_id" :danger="0 < errors.length" :danger-text="errors[0]" label="Strand" placeholder="Select Strand" class="mb-2">
                                        <vs-select-item v-for="(strand, index) in $store.state.options.strands" :key="index" :value="strand.value" :text="strand.text"></vs-select-item>
                                    </vs-select>
                                </validation-provider>
                                <p v-if="0 == $store.state.options.strands.length">
                                    No strands found. Please create <router-link :to="{ name: 'strand_new' }">here.</router-link>
                                </p>

                                <vs-divider class="my-3"/>

                                <vs-button button="submit">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                                <vs-button @click="$router.push({ name: 'classes' })" color="grey" class="float-right">Cancel</vs-button>
                            </div>
                        </vs-card>
                    </vs-col>
                </vs-row>
            </form>
        </validation-observer>
    </div>
</template>

<script>
    import { ValidationObserver, ValidationProvider } from 'vee-validate'

    export default {
        components  : {
            'validation-observer'   : ValidationObserver,
            'validation-provider'   : ValidationProvider
        },
        data() {
            return {
                models  : {
                    class       : {}
                }
            }
        },
        methods     : {
            create() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('createDataBySource', { source: 'classes', data: this.models.class }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'New class created.', color: 'success' })

                        this.$router.push({ name: 'classes' })
                    }).catch(error => {
                        this.$vs.notify({ title: 'Warning', text: error.response.data.response, color: 'warning' })
                    })
                })
            },
            update() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('updateDataBySource', { source: 'classes', id: this.models.class.id, data: this.models.class }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Class updated.', color: 'success' })

                        this.$router.push({ name: 'classes' })
                    }).catch(error => {
                        this.$vs.notify({ title: 'Warning', text: error.response.data.response, color: 'warning' })
                    })
                })
            },
            getSectionsByAcademicYear() {
                this.models.class.section_id = null

                this.$store.dispatch('getSectionsByAcademicYear', { id: this.models.class.academic_year_id })
            },
            getSubjectsBySemester() {
                let _semester = 1 == this.models.class.semester ? 'first' : 'second'

                this.$store.dispatch('getDatasBySource', { source: 'subjects', status: 'published', filters: `semester=${_semester}`, no_commit: true }).then(response => {
                    this.$store.commit('SET_OPTIONS', { key: 'subjects', options: response.data.response })
                })
            }
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'academic_years' })
            this.$store.dispatch('getSelectOptions', { source: 'subjects' })
            this.$store.dispatch('getSelectOptions', { source: 'sections' })
            this.$store.dispatch('getSelectOptions', { source: 'strands' })
            this.$store.dispatch('getSelectOptions', { source: 'levels' })

            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'classes', id: this.$route.params.id }).then(response => {
                        this.models.class = response.data.response
                    }).catch(_ => {
                        this.$router.push({ name: 'classes' })
                    })
                } else {
                    this.models.class = this.$store.state.apiData.active
                }
            }
        }
    }
</script>