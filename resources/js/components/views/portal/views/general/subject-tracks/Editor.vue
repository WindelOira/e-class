<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="my-1 float-left">{{ $route.params.id ? 'Update' : 'New' }} Track</h3>
                <vs-button v-if="$route.params.id" @click="$router.push({ name: 'subject-track_new' })" class="float-right">Add New</vs-button>
            </vs-col>
        </vs-row>

        <validation-observer ref="observer">
            <form @submit.prevent="$route.params.id ? update() : create()">
                <vs-row>
                    <vs-col vs-xs="12" vs-sm="7" vs-lg="9">
                        <vs-card>
                            <p class="mb-3">
                                <router-link :to="{ name: 'subject-tracks' }">
                                    <small>&laquo; Return to all subject tracks</small>
                                </router-link>
                            </p>

                            <vs-row>
                                <vs-col>
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.track.name" :danger="0 < errors.length" :danger-text="errors[0]" label="Track" class="mb-4"></vs-input>
                                    </validation-provider>

                                    <vs-row>
                                        <vs-col vs-xs="4" vs-sm="4" vs-lg="4">
                                            <validation-provider rules="required|numeric" v-slot="{errors}">
                                                <vs-input v-model="models.track.written_work" @change="calcRemaining" :danger="0 < errors.length" :danger-text="errors[0]" :label="`Written Work (${models.track.written_work}%)`"></vs-input>
                                            </validation-provider>
                                        </vs-col>
                                        <vs-col vs-xs="4" vs-sm="4" vs-lg="4">
                                            <validation-provider rules="required|numeric" v-slot="{errors}">
                                                <vs-input v-model="models.track.performance_task" @change="calcRemaining" :danger="0 < errors.length" :danger-text="errors[0]" :label="`Performance Task (${models.track.performance_task}%)`"></vs-input>
                                            </validation-provider>
                                        </vs-col>
                                        <vs-col vs-xs="4" vs-sm="4" vs-lg="4">
                                            <validation-provider rules="required|numeric" v-slot="{errors}">
                                                <vs-input v-model="models.track.quarterly_assessment" @change="calcRemaining" :danger="0 < errors.length" :danger-text="errors[0]" :label="`Quarterly Assessment (${models.track.quarterly_assessment}%)`"></vs-input>
                                            </validation-provider>
                                        </vs-col>
                                    </vs-row>
                                </vs-col>
                            </vs-row>
                        </vs-card>
                    </vs-col>
                    <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                        <vs-card>
                            <div>
                                <vs-button :disabled="(0 == settings.status)" button="submit">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                                <vs-button @click="$router.push({ name: 'subject-tracks' })" color="grey" class="float-right">Cancel</vs-button>
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

    export default {
        components  : {
            'validation-observer'   : ValidationObserver,
            'validation-provider'   : ValidationProvider
        },
        data() {
            return {
                models  : {
                    track       : {
                        written_work            : '',
                        performance_task        : '',
                        quarterly_assessment    : ''
                    },
                    percentage  : {
                        status      : 'success',
                        remaining   : 100
                    }
                }
            }
        },
        computed    : {
            ...mapGetters([
                'settings'
            ])
        },
        methods     : {
            create() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    if( 0 < this.models.percentage.remaining ) {
                        this.$vs.notify({ title: 'Warning', text: 'Percentages should be a total of 100%.', color: 'warning' })
                        return false;
                    }

                    if( this.models.percentage.remaining < 0 ) {
                        this.$vs.notify({ title: 'Warning', text: 'Percentages should not be greater than 100%.', color: 'warning' })
                        return false;
                    }

                    this.$store.dispatch('createDataBySource', { source: 'subject_tracks', data: this.models.track }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'New subject track created.', color: 'success' })

                        this.$router.push({ name: 'subject-tracks' })
                    }).catch(error => {
                        this.$vs.notify({ title: 'Warning', text: error.response.data.response, color: 'warning' })
                    })
                })
            },
            update() {                
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    if( 0 < this.models.percentage.remaining ) {
                        this.$vs.notify({ title: 'Warning', text: 'Percentages should be a total of 100%.', color: 'warning' })
                        return false;
                    }

                    if( this.models.percentage.remaining < 0 ) {
                        this.$vs.notify({ title: 'Warning', text: 'Percentages should not be greater than 100%.', color: 'warning' })
                        return false;
                    }

                    this.$store.dispatch('updateDataBySource', { source: 'subject_tracks', id: this.models.track.id, data: this.models.track }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Subject track updated.', color: 'success' })

                        this.$router.push({ name: 'subject-tracks' })
                    }).catch(error => {
                        this.$vs.notify({ title: 'Warning', text: error.response.data.response, color: 'warning' })
                    })
                })
            },
            calcRemaining(value) {
                this.models.percentage.remaining = 100 - (parseInt(this.models.track.written_work) + parseInt(this.models.track.performance_task) + parseInt(this.models.track.quarterly_assessment))
                this.models.percentage.status = this.models.percentage.remaining < 0 ? 'danger' : 'success'
            }
        },
        created() {
            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'subject_tracks', id: this.$route.params.id }).then(response => {
                        this.models.track = response.data.response
                    }).catch(_ => {
                        this.$router.push({ name: 'subject-tracks' })
                    })
                } else {
                    this.models.track = this.$store.state.apiData.active
                }

                this.models.percentage.remaining = 0
            }
        }
    }
</script>