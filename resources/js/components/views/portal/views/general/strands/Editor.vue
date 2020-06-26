<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="my-1 float-left">{{ $route.params.id ? 'Update' : 'New' }} Strand</h3>
                <vs-button v-if="$route.params.id" @click="$router.push({ name: 'strand_new' })" class="float-right">Add New</vs-button>
            </vs-col>
        </vs-row>

        <validation-provider ref="observer">
            <form @submit.prevent="$route.params.id ? update() : create()">
                <vs-row>
                    <vs-col vs-xs="12" vs-sm="7" vs-lg="9">
                        <vs-card>
                            <p class="mb-3">
                                <router-link :to="{ name: 'strands' }">
                                    <small>&laquo; Return to all strands</small>
                                </router-link>
                            </p>
                            
                            <vs-row>
                                <vs-col>
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.strand.code" :danger="0 < errors.length" :danger-text="errors[0]" label="Code" class="mb-3"></vs-input>
                                    </validation-provider>
                                    
                                    <vs-textarea v-model="models.strand.description" label="Description"></vs-textarea>
                                </vs-col>
                            </vs-row>
                        </vs-card>
                    </vs-col>
                    <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                        <vs-card>
                            <div>
                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-select v-model="models.strand.track_id" :danger="0 < errors.length" :danger-text="errors[0]" label="Track" placeholder="Select Track" class="mb-2">
                                        <vs-select-item v-for="(track, index) in $store.state.options.tracks" :key="index" :value="track.value" :text="track.text"/>
                                    </vs-select>
                                </validation-provider>
                                <p v-if="0 == $store.state.options.tracks.length">
                                    No tracks found. Please create <router-link :to="{ name: 'track_new' }">here.</router-link>
                                </p>

                                <vs-button button="submit">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                                <vs-button @click="$router.push({ name: 'strands' })" color="grey" class="float-right">Cancel</vs-button>
                            </div>
                        </vs-card>
                    </vs-col>
                </vs-row>
            </form>
        </validation-provider>
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
                    strand      : {}
                }
            }
        },
        methods     : {
            create() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('createDataBySource', { source: 'strands', data: this.models.strand }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'New strand created.', color: 'success' })

                        this.$router.push({ name: 'strands' })
                    }).catch(error => {
                        this.$vs.notify({ title: 'Warning', text: error.response.data.response, color: 'warning' })
                    })
                })
            },
            update() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('updateDataBySource', { source: 'strands', id: this.models.strand.id, data: this.models.strand }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Strand updated.', color: 'success' })
                    }).catch(error => {
                        this.$vs.notify({ title: 'Warning', text: error.response.data.response, color: 'warning' })
                    })
                })
            }
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'tracks' })

            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'strands', id: this.$route.params.id }).then(response => {
                        this.models.strand = response.data.response
                    }).catch(_ => {
                        this.$router.push({ name: 'strands' })
                    })
                } else {
                    this.models.strand = this.$store.state.apiData.active
                }
            }
        }
    }
</script>