<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="my-1 float-left">{{ $route.params.id ? 'Update' : 'New' }} Computation Variable</h3>
                <vs-button v-if="$route.params.id" @click="$router.push({ name: 'computation-variables_new' })" class="float-right">Add New</vs-button>
            </vs-col>
        </vs-row>

        <vs-row>
            <vs-col vs-xs="12" vs-sm="7" vs-lg="9">
                <vs-card>
                    <p class="mb-3">
                        <router-link :to="{ name: 'computation-variables' }">
                            <small>&laquo; Return to all computation variables</small>
                        </router-link>
                    </p>
                    
                    <vs-row>
                        <vs-col vs-xs="12" vs-sm="6" vs-lg="4">
                            <vs-select v-model="models.computation_variable.type" label="Type" placeholder="Select Type" class="mb-3">
                                <vs-select-item value="rating-sheets" text="Rating Sheets"></vs-select-item>
                            </vs-select>
                        </vs-col>
                    </vs-row>

                    <vs-row>
                        <vs-col vs-xs="12" vs-sm="6" vs-lg="6">
                            <vs-input v-model="models.computation_variable.name" label="Name" class="mb-3"></vs-input>
                        </vs-col>
                        <vs-col vs-xs="12" vs-sm="6" vs-lg="6">
                            <vs-input v-model="models.computation_variable.percentage" label="Percentage"></vs-input>
                        </vs-col>
                    </vs-row>
                </vs-card>
            </vs-col>
            <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                <vs-card>
                    <div>
                        <vs-button @click="$route.params.id ? update() : create()" :disabled="!valid">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                        <vs-button @click="$router.push({ name: 'computation-variables' })" color="grey" class="float-right">Cancel</vs-button>
                    </div>
                </vs-card>
            </vs-col>
        </vs-row>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                models  : {
                    computation_variable    : {}
                }
            }
        },
        methods     : {
            create() {
                this.$store.dispatch('createDataBySource', { source: 'computation-variables', data: this.models.computation_variable }).then(response => {
                    this.$vs.notify({ title: 'Success', text: 'New computation variable created.', color: 'success' })

                    this.$router.push({ name: 'computation-variables_edit', params: { id: response.data.response.id } })
                })
            },
            update() {
                this.$store.dispatch('updateDataBySource', { source: 'computation-variables', id: this.models.computation_variable.id, data: this.models.computation_variable }).then(response => {
                    this.$vs.notify({ title: 'Success', text: 'Computation variable updated.', color: 'success' })
                })
            }
        },
        computed    : {
            valid() {
                if( !this.models.computation_variable.type )
                    return false;

                if( !this.models.computation_variable.name )
                    return false;

                if( !this.models.computation_variable.percentage )
                    return false;

                return true
            }
        },
        created() {
            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'computation-variables', id: this.$route.params.id }).then(response => {
                        this.models.computation_variable = response.data.response
                    }).catch(_ => {
                        this.$router.push({ name: 'computation-variables' })
                    })
                } else {
                    this.models.computation_variable = this.$store.state.apiData.active
                }
            }
        }
    }
</script>