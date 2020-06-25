<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="my-1 float-left">{{ $route.params.id ? 'Update' : 'New' }} Attendance</h3>
                <vs-button v-if="$route.params.id" @click="$router.push({ name: 'attendance_new' })" class="float-right">Add New</vs-button>
            </vs-col>
        </vs-row>

        <validation-observer ref="observer">
            <vs-row>
                <vs-col vs-xs="12" vs-sm="7" vs-lg="9">
                    <vs-card>
                        <p class="mb-3">
                            <router-link :to="{ name: 'attendances' }">
                                <small>&laquo; Return to all attendances</small>
                            </router-link>
                        </p>
                        
                        <div>
                            <vs-row>
                                <vs-col vs-xs="12" vs-sm="6" vs-lg="4">
                                    <div class="vs-component vs-con-input-label">
                                        <label for="date" class="vs-input--label">Date</label>
                                        <v-datepicker v-model="models.attendance.date" input-class="vs-input--input" id="year" class="mb-3"></v-datepicker>
                                    </div>
                                </vs-col>
                                <vs-col vs-xs="12" vs-sm="6" vs-lg="4">
                                    <div class="vs-component vs-con-input-label">
                                        <label for="time" class="vs-input--label">Time</label>
                                        <v-timepicker v-model="models.attendance.time" format="hh:mm:ss" input-class="vs-input--input" id="time" class="mb-3"></v-timepicker>
                                    </div>
                                </vs-col>
                            </vs-row>

                            <h4 class="mt-4 mb-3">Select Present Students</h4>

                            <vs-row class="mb-4">
                                <vs-col vs-xs="12" vs-sm="6" vs-lg="3">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-select @change="getStudents()" :danger="0 < errors.length" :danger-text="errors[0]" placeholder="Select by level">
                                            <vs-select-item v-for="(level, index) in $store.state.options.levels" :key="index" :value="level.value" :text="level.text"></vs-select-item>
                                        </vs-select>
                                    </validation-provider>
                                </vs-col>
                                <vs-col vs-xs="12" vs-sm="6" vs-lg="3">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-select @change="getStudents()" :danger="0 < errors.length" :danger-text="errors[0]" placeholder="Select by strand">
                                            <vs-select-item v-for="(strand, index) in $store.state.options.strands" :key="index" :value="strand.value" :text="strand.text"></vs-select-item>
                                        </vs-select>
                                    </validation-provider>
                                </vs-col>
                            </vs-row>

                            <vs-row>
                                <vs-col v-for="(student, index) in students" :key="index" vs-xs="6" vs-sm="3" vs-lg="2" class="text-left">
                                    <vs-checkbox v-model="models.attendance.present" :vs-value="student.id">{{ student.name }}</vs-checkbox>
                                </vs-col>
                            </vs-row>
                        </div>
                    </vs-card>
                </vs-col>
                <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                    <vs-card>
                        <div>
                            <vs-button @click="$route.params.id ? update() : create()" :disabled="!valid">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                            <vs-button @click="$router.push({ name: 'attendances' })" color="grey" class="float-right">Cancel</vs-button>
                        </div>
                    </vs-card>
                </vs-col>
            </vs-row>
        </validation-observer>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker'
    import VueTimepicker from 'vue2-timepicker'
    import { ValidationObserver, ValidationProvider } from 'vee-validate'

    export default {
        components  : {
            'v-datepicker'          : Datepicker,
            'v-timepicker'          : VueTimepicker,
            'validation-observer'   : ValidationObserver,
            'validation-provider'   : ValidationProvider
        },
        data() {
            return {
                models      : {
                    attendance      : {
                        date            : new Date(),
                        time            : '',
                        present         : [],
                        date_time       : null
                    }
                },
                students    : []
            }
        },
        watch           : {
            'models.attendance.date'    : function(val, oval) {
                this.models.attendance.date_time = new Date(this.models.attendance.date).toISOString().slice(0, 10) +' '+ this.models.attendance.time
            },
            'models.attendance.time'    : function(val, oval) {
                this.models.attendance.date_time = new Date(this.models.attendance.date).toISOString().slice(0, 10) +' '+ this.models.attendance.time
            }
        },
        methods         : {
            create() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('createDataBySource', { source: 'attendances', data: this.models.attendance }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'New attendance created.', color: 'success' })

                        this.$router.push({ name: 'attendance_edit', params: { id: response.data.response } })
                    })
                })
            },
            update() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('updateDataBySource', { source: 'attendances', id: this.models.attendance.id, data: this.models.attendance }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Attendance updated.', color: 'success' })
                    })
                })
            }
        },
        computed        : {
            valid() {
                if( !this.models.attendance.date ) 
                    return false;

                if( !this.models.attendance.time ) 
                    return false;

                return true
            }
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'strands' })
            this.$store.dispatch('getSelectOptions', { source: 'levels' })
            this.$store.dispatch('getDatasBySource', { source: 'students', status: 'published', no_commit: true }).then(response => {
                if( 0 < response.data.response.length ) {
                    this.students = response.data.response

                    if( 0 < response.data.response.length ) {
                        response.data.response.forEach(student => {
                            this.models.attendance.present.push(student.id)
                        })
                    }
                }
            })

            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'attendances', id: this.$route.params.id }).then(response => {
                        this.models.attendance = response.data.response
                    }).catch(_ => {
                        this.$router.push({ name: 'attendances' })
                    })
                } else {
                    this.models.attendance = this.$store.state.apiData.active
                }
            }
        }
    }
</script>