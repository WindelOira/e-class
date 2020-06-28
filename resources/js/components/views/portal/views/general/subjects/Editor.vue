<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="mb-3">{{ $route.params.id ? 'Update' : 'New' }} Subject</h3>
                <router-link :to="{ name: 'subjects' }">
                    <small>&laquo; Return to all subjects</small>
                </router-link>
            </vs-col>
        </vs-row>

        <validation-observer ref="observer">
            <form @submit.prevent="$route.params.id ? update() : create()">
                <vs-row>
                    <vs-col vs-xs="12" vs-sm="7" vs-lg="9">
                        <vs-card>
                            <vs-row>
                                <vs-col vs-xs="12" vs-md="8" vs-lg="9">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.subject.name" :danger="0 < errors.length" :danger-text="errors[0]" label="Subject" class="mb-3"></vs-input>
                                    </validation-provider>
                                </vs-col>
                                <vs-col vs-xs="12" vs-md="4" vs-lg="3">
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.subject.hours" :danger="0 < errors.length" :danger-text="errors[0]" label="Hours" class="mb-3"></vs-input>
                                    </validation-provider>
                                </vs-col>
                            </vs-row>

                            <vs-row>
                                <vs-col>
                                    <vs-textarea v-model="models.subject.description" label="Description"></vs-textarea>
                                </vs-col>
                            </vs-row>
                        </vs-card>
                    </vs-col>
                    <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                        <vs-card>
                            <div>
                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-select v-model="models.subject.subject_track_id" :danger="0 < errors.length" :danger-text="errors[0]" label="Track" placeholder="Select Track" class="mb-2">
                                        <vs-select-item v-for="(subject_track, index) in $store.state.options.subject_tracks" :key="index" :value="subject_track.value" :text="subject_track.text"/>
                                    </vs-select>
                                </validation-provider>
                                <p v-if="0 == $store.state.options.subject_tracks.length">
                                    No subject tracks found. Please create <router-link :to="{ name: 'subject-track_new' }">here.</router-link>
                                </p>

                                <vs-divider class="my-3"/>

                                <vs-button button="submit">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                                <vs-button @click="$router.push({ name: 'subjects' })" color="grey" class="float-right">Cancel</vs-button>
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
                    subject     : {}
                }
            }
        },
        methods     : {
            create() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('createDataBySource', { source: 'subjects', data: this.models.subject }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'New subject created.', color: 'success' })

                        this.$router.push({ name: 'subjects' })
                    }).catch(error => {
                        this.$vs.notify({ title: 'Warning', text: error.response.data.response, color: 'warning' })
                    })
                })
            },
            update() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('updateDataBySource', { source: 'subjects', id: this.models.subject.id, data: this.models.subject }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Subject updated.', color: 'success' })

                        this.$router.push({ name: 'subjects' })
                    }).catch(error => {
                        this.$vs.notify({ title: 'Warning', text: error.response.data.response, color: 'warning' })
                    })
                })
            }
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'subject_tracks' })
            
            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'subjects', id: this.$route.params.id }).then(response => {
                        this.models.subject = response.data.response
                    }).catch(_ => {
                        this.$router.push({ name: 'subjects' })
                    })
                } else {
                    this.models.subject = this.$store.state.apiData.active
                }
            }
        }
    }
</script>