<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="my-1 float-left">{{ $route.params.id ? 'Update' : 'New' }} Student</h3>
                <vs-button v-if="$route.params.id" @click="$router.push({ name: 'level_new' })" class="float-right">Add New</vs-button>
            </vs-col>
        </vs-row>

        <validation-observer ref="observer">
            <vs-row>
                <vs-col vs-xs="12" vs-sm="7" vs-lg="9">
                    <vs-card>
                        <p class="mb-3">
                            <router-link :to="{ name: 'students' }">
                                <small>&laquo; Return to all students</small>
                            </router-link>
                        </p>
                        
                        <div>
                            <h4 class="mb-2">Student Information</h4>

                            <vs-row>
                                <vs-col vs-xs="12" vs-sm="8" vs-lg="6">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.student.student_id" :danger="0 < errors.length" :danger-text="errors[0]" :disabled="$route.params.id" label="Student ID" class="mb-3"></vs-input>
                                    </validation-provider>
                                </vs-col>
                            </vs-row>

                            <vs-row>
                                <vs-col vs-xs="12" vs-sm="12" vs-lg="4">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.student.meta.fname" :danger="0 < errors.length" :danger-text="errors[0]" label="First Name" class="mb-3"></vs-input>
                                    </validation-provider>
                                </vs-col>
                                <vs-col vs-xs="12" vs-sm="12" vs-lg="4">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.student.meta.mname" :danger="0 < errors.length" :danger-text="errors[0]" label="Middle Name" class="mb-3"></vs-input>
                                    </validation-provider>
                                </vs-col>
                                <vs-col vs-xs="12" vs-sm="12" vs-lg="4">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.student.meta.lname" :danger="0 < errors.length" :danger-text="errors[0]" label="Last Name" class="mb-3"></vs-input>
                                    </validation-provider>
                                </vs-col>
                            </vs-row>

                            <vs-row>
                                <vs-col vs-xs="12" vs-sm="12" vs-lg="6">
                                    <vs-select v-model="models.student.meta.gender" label="Gender" class="mb-3">
                                        <vs-select-item v-for="(gender, index) in $store.state.options.genders" :key="index" :value="gender.value" :text="gender.text"/>
                                    </vs-select>
                                </vs-col>
                                <vs-col vs-xs="12" vs-sm="12" vs-lg="6">

                                </vs-col>
                            </vs-row>

                            <vs-row>
                                <vs-col>
                                    <vs-textarea v-model="models.student.meta.address" label="Address" class="mb-0"></vs-textarea>
                                </vs-col>
                            </vs-row>
                        </div>
                    </vs-card>
                </vs-col>
                <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                    <vs-card>
                        <div>
                            <vs-button @click="$route.params.id ? update() : create()">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                            <vs-button @click="$router.push({ name: 'students' })" color="grey" class="float-right">Cancel</vs-button>
                        </div>
                    </vs-card>
                </vs-col>
            </vs-row>
        </validation-observer>
    </div>
</template>

<script>
    import { ValidationObserver, ValidationProvider } from 'vee-validate'

    export default {
        components      : {
            'validation-observer'   : ValidationObserver,
            'validation-provider'   : ValidationProvider
        },
        data() {
            return {
                models  : {
                    student     : {
                        meta        : {
                            gender      : 'm'
                        }
                    }
                }
            }
        },
        methods         : {
            create() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('createDataBySource', { source: 'students', data: this.models.student }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'New student created.', color: 'success' })

                        this.$router.push({ name: 'student_edit', params: { id: response.data.response } })
                    })
                })
            },
            update() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('updateDataBySource', { source: 'students', id: this.models.student.id, data: this.models.student }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Student updated.', color: 'success' })
                    })
                })
            }
        },
        created() {
            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'students', id: this.$route.params.id }).then(response => {
                        // this.models.student = response.data.response
                        this.models.student.student_id = response.data.response.student_id
                    }).catch(_ => {
                        this.$router.push({ name: 'students' })
                    })
                } else {
                    this.models.student = this.$store.state.apiData.active
                }
            }
        }
    }
</script>