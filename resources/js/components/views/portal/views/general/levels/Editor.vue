<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="my-1 float-left">{{ $route.params.id ? 'Update' : 'New' }} Grade Level</h3>
                <vs-button v-if="$route.params.id" @click="$router.push({ name: 'level_new' })" class="float-right">Add New</vs-button>
            </vs-col>
        </vs-row>

        <validation-observer ref="observer">
            <form @submit.prevent="$route.params.id ? update() : create()">
                <vs-row>
                    <vs-col vs-xs="12" vs-sm="7" vs-lg="9">
                        <vs-card>
                            <p class="mb-3">
                                <router-link :to="{ name: 'levels' }">
                                    <small>&laquo; Return to all grade levels</small>
                                </router-link>
                            </p>

                            <vs-row>
                                <vs-col>
                                    <validation-provider rules="required" v-slot="{errors}">
                                        <vs-input v-model="models.level.name" :danger="0 < errors.length" :danger-text="errors[0]" label="Grade Level"></vs-input>
                                    </validation-provider>
                                </vs-col>
                            </vs-row>
                        </vs-card>
                    </vs-col>
                    <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                        <vs-card>
                            <div>
                                <vs-button button="submit">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                                <vs-button @click="$router.push({ name: 'levels' })" color="grey" class="float-right">Cancel</vs-button>
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
                    level       : {}
                }
            }
        },
        methods     : {
            create() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('createDataBySource', { source: 'levels', data: this.models.level }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'New level created.', color: 'success' })

                        this.$router.push({ name: 'levels' })
                    })
                })
            },
            update() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('updateDataBySource', { source: 'levels', id: this.models.level.id, data: this.models.level }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Level updated.', color: 'success' })

                        this.$router.push({ name: 'levels' })
                    })
                })
            }
        },
        created() {
            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'levels', id: this.$route.params.id }).then(response => {
                        this.models.level = response.data.response
                    }).catch(_ => {
                        this.$router.push({ name: 'levels' })
                    })
                } else {
                    this.models.level = this.$store.state.apiData.active
                }
            } else {
                this.models.level = {}
            }
        }
    }
</script>