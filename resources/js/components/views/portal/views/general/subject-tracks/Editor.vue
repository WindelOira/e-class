<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="my-1 float-left">{{ $route.params.id ? 'Update' : 'New' }} Track</h3>
                <vs-button v-if="$route.params.id" @click="$router.push({ name: 'subject-track_new' })" class="float-right">Add New</vs-button>
            </vs-col>
        </vs-row>

        <validation-observer ref="observer">
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

                                <div class="vs-component vs-con-input-label mb-3">
                                    <label class="vs-input--label">Written Work ({{ models.track.written_work }}%)</label>
                                    <vs-slider v-model="models.track.written_work" @change="calcRemaining" :color="models.percentage.status" text-fixed="%" step-decimals/>
                                </div>

                                <div class="vs-component vs-con-input-label mb-3">
                                    <label class="vs-input--label">Performance Task ({{ models.track.performance_task }}%)</label>
                                    <vs-slider v-model="models.track.performance_task" @change="calcRemaining" :color="models.percentage.status" text-fixed="%" step-decimals/>
                                </div>

                                <div class="vs-component vs-con-input-label mb-3">
                                    <label class="vs-input--label">Quarterly Assessment ({{ models.track.quarterly_assessment }}%)</label>
                                    <vs-slider v-model="models.track.quarterly_assessment" @change="calcRemaining" :color="models.percentage.status" text-fixed="%" step-decimals/>
                                </div>
                            </vs-col>
                        </vs-row>
                    </vs-card>
                </vs-col>
                <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                    <vs-card>
                        <div>
                            <vs-button @click="$route.params.id ? update() : create()">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                            <vs-button @click="$router.push({ name: 'subject-tracks' })" color="grey" class="float-right">Cancel</vs-button>
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
        components  : {
            'validation-observer'   : ValidationObserver,
            'validation-provider'   : ValidationProvider
        },
        data() {
            return {
                models  : {
                    track       : {
                        written_work            : 0,
                        performance_task        : 0,
                        quarterly_assessment    : 0
                    },
                    percentage  : {
                        status      : 'success',
                        remaining   : 100
                    }
                }
            }
        },
        methods     : {
            create() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    if( 'danger' == this.models.percentage.status )
                        return false;

                    this.$store.dispatch('createDataBySource', { source: 'subject_tracks', data: this.models.track }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'New subject track created.', color: 'success' })

                        this.$router.push({ name: 'subject-track_edit', params: { id: response.data.response.id } })
                    })
                })
            },
            update() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    if( 'danger' == this.models.percentage.status )
                        return false;

                    this.$store.dispatch('updateDataBySource', { source: 'subject_tracks', id: this.models.track.id, data: this.models.track }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Subject track updated.', color: 'success' })
                    })
                })
            },
            calcRemaining(value) {
                this.models.percentage.remaining = 100 - (this.models.track.written_work + this.models.track.performance_task + this.models.track.quarterly_assessment)
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
            }
        }
    }
</script>