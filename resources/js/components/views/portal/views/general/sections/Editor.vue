<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="my-1 float-left">{{ $route.params.id ? 'Update' : 'New' }} Section</h3>
                <vs-button v-if="$route.params.id" @click="$router.push({ name: 'section_new' })" class="float-right">Add New</vs-button>
            </vs-col>
        </vs-row>

        <validation-observer ref="observer">
            <form @submit.prevent="$route.params.id ? update() : create()">
                <vs-row>
                    <vs-col vs-xs="12" vs-sm="7" vs-lg="9">
                        <vs-card>
                            <p class="mb-3">
                                <router-link :to="{ name: 'sections' }">
                                    <small>&laquo; Return to all sections</small>
                                </router-link>
                            </p>

                            <vs-row>
                                <vs-col vs-xs="12" vs-sm="6" vs-lg="4">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-select v-model="models.section.academic_year_id" :danger="0 < errors.length" :danger-text="errors[0]" label="Academic Year" placeholder="Select Academic Year" class="mb-2">
                                            <vs-select-item v-for="(academic_year, index) in options.academic_years" :key="index" :value="academic_year.value" :text="academic_year.text"></vs-select-item>
                                        </vs-select>
                                    </validation-provider>
                                    <p v-if="0 == options.academic_years.length" class="mb-3">
                                        No academic years found. Please create <router-link :to="{ name: 'academic-year_new' }">here.</router-link>
                                    </p>
                                </vs-col>
                            </vs-row>

                            <vs-row>
                                <vs-col>
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.section.name" :danger="0 < errors.length" :danger-text="errors[0]" label="Name" class="mb-3"></vs-input>
                                    </validation-provider>
                                </vs-col>
                            </vs-row>

                            <h3>Students</h3>
                            <vs-divider></vs-divider>

                            <vs-row v-if="students.length">
                                <vs-col>
                                    <v-multiselect-listbox 
                                        v-model="models.section.students" 
                                        :options="students" 
                                        :reduce-display-property="(student) => `Student #${student.student_number} : ${student.name}`"
                                        :reduce-value-property="(student) => student.id" 
                                        search-input-class="vs-input--input" 
                                        search-options-placeholder="Search students"
                                        selected-options-placeholder="Search selected students"></v-multiselect-listbox>
                                </vs-col>
                            </vs-row>
                            <p v-else>
                                No students found. Create <router-link :to="{ name: 'student_new' }">here</router-link>.
                            </p>
                        </vs-card>
                    </vs-col>
                    <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                        <vs-card>
                            <div>
                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-select v-model="models.section.strand_id" @change="getStudents(models.section.strand_id)" :danger="0 < errors.length" :danger-text="errors[0]" label="Strand" placeholder="Select Strand" class="mb-2">
                                        <vs-select-item v-for="(strand, index) in options.strands" :key="index" :value="strand.value" :text="strand.text"/>
                                    </vs-select>
                                </validation-provider>
                                <p v-if="0 == options.strands.length" class="mb-3">
                                    No strands found. Please create <router-link :to="{ name: 'strand_new' }">here.</router-link>
                                </p>

                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-select v-model="models.section.level_id" :danger="0 < errors.length" :danger-text="errors[0]" label="Grade Level" placeholder="Select Grade Level" class="mb-2">
                                        <vs-select-item v-for="(level, index) in options.levels" :key="index" :value="level.value" :text="level.text"/>
                                    </vs-select>
                                </validation-provider>
                                <p v-if="0 == options.levels.length" class="mb-3">
                                    No grade levels found. Please create <router-link :to="{ name: 'level_new' }">here.</router-link>
                                </p>

                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-select v-model="models.section.user_id" :danger="0 < errors.length" :danger-text="errors[0]" label="Adviser" placeholder="Select Adviser" class="mb-2">
                                        <vs-select-item v-for="(adviser, index) in options.advisers" :key="index" :value="adviser.value" :text="adviser.text"></vs-select-item>
                                    </vs-select>
                                </validation-provider>
                                <p v-if="0 == options.advisers.length">
                                    No advisers found. Please create <router-link :to="{ name: 'user_new' }">here.</router-link>
                                </p>

                                <vs-divider class="my-3"/>

                                <vs-button :disabled="(0 == settings.status)" button="submit">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                                <vs-button @click="$router.push({ name: 'sections' })" color="grey" class="float-right">Cancel</vs-button>
                            </div>
                        </vs-card>
                    </vs-col>
                </vs-row>
            </form>
        </validation-observer>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import { ValidationObserver, ValidationProvider } from 'vee-validate'
    import vMultiselectListbox from 'vue-multiselect-listbox'

    export default {
        components  : {
            'validation-observer'   : ValidationObserver,
            'validation-provider'   : ValidationProvider,
            'v-multiselect-listbox'  : vMultiselectListbox
        },
        data() {
            return {
                models  : {
                    section     : {
                        students    : []
                    }
                },
                students : []
            }
        },
        methods     : {
            create() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('createDataBySource', { source: 'sections', data: this.models.section }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'New section created.', color: 'success' })

                        this.$router.push({ name: 'sections' })
                    })
                })
            },
            update() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('updateDataBySource', { source: 'sections', id: this.models.section.id, data: this.models.section }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Section updated.', color: 'success' })

                        this.$router.push({ name: 'sections' })
                    })
                })
            },
            getStudents(strand = false) {
                if( !this.$route.params.id ) {
                    this.students = []
                }

                this.$store.dispatch('getDatasBySource', { source: 'students', status: 'published', filters: strand ? `strand_id=${strand}` : null, no_commit: true }).then(response => {
                    this.students = response.data.response.filter(student => {
                        if( this.$route.params.id ) {
                            return !student.section_id || (student.section_id == this.$route.params.id)
                        } else {
                            return !student.section_id
                        }
                    })
                })
            }
        },
        computed    : {
            ...mapGetters([
                'options', 'settings'
            ])
        },
        watch       : {
            $route( to, from ) {
                if( 'section_new' == to.name ) {
                    this.getStudents()
                }
            }
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'academic_years' })
            this.$store.dispatch('getSelectOptions', { source: 'strands' })
            this.$store.dispatch('getSelectOptions', { source: 'levels' })
            this.$store.dispatch('getSelectOptions', { source: 'users', alias: 'advisers', filters: [{ key: 'role', value: 'teacher' }] })
            
            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'sections', id: this.$route.params.id }).then(response => {
                        this.models.section = response.data.response
                       
                        this.getStudents(this.models.section.strand_id)
                    }).catch(_ => {
                        this.$router.push({ name: 'sections' })
                    })
                } else {
                    this.models.section = this.$store.state.apiData.active
                    
                    this.getStudents(this.models.section.strand_id)
                }
            } else {
                this.getStudents()
            }
        }
    }
</script>