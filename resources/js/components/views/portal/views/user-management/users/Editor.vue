<template>
    <div>
        <vs-row class="mb-4">
            <vs-col>
                <h3 class="mb-3">{{ $route.params.id ? 'Update' : 'New' }} Account</h3>
                <router-link :to="{ name: 'users' }">
                    <small>&laquo; Return to all users</small>
                </router-link>
            </vs-col>
        </vs-row>

        <vs-row>
            <vs-col vs-xs="12" vs-sm="7" vs-lg="9">
                <vs-card>
                    <div>
                        <h4 class="mb-2">User Information</h4>

                        <vs-row>
                            <vs-col vs-xs="12" vs-sm="8" vs-lg="6" class="mb-2">
                                <vs-select v-model="models.user.role">
                                    <vs-select-item v-for="(role, index) in $store.state.options.roles" :key="index" :value="role.value" :text="role.text"/>
                                </vs-select>
                            </vs-col>
                        </vs-row>

                        <vs-row>
                            <vs-col vs-xs="12" vs-sm="8" vs-lg="6">
                                <vs-input v-model="models.user.employee_id" label-placeholder="Employee ID" class="mb-2"></vs-input>
                            </vs-col>
                        </vs-row>

                        <vs-row>
                            <vs-col vs-xs="12" vs-sm="12" vs-lg="4">
                                <vs-input v-model="models.user.meta.fname" label-placeholder="First Name" class="mb-3"></vs-input>
                            </vs-col>
                            <vs-col vs-xs="12" vs-sm="12" vs-lg="4">
                                <vs-input v-model="models.user.meta.mname" label-placeholder="Middle Name" class="mb-3"></vs-input>
                            </vs-col>
                            <vs-col vs-xs="12" vs-sm="12" vs-lg="4">
                                <vs-input v-model="models.user.meta.lname" label-placeholder="Last Name" class="mb-3"></vs-input>
                            </vs-col>
                        </vs-row>

                        <vs-row>
                            <vs-col>
                                <vs-textarea v-model="models.user.meta.address" label="Address" class="mb-0"></vs-textarea>
                            </vs-col>
                        </vs-row>

                        <vs-divider/>

                        <h4 class="mb-2">Login Details</h4>

                        <vs-row>
                            <vs-col vs-xs="12" vs-sm="6" vs-lg="6">
                                <vs-input v-model="models.user.email" label-placeholder="Email Address" type="email" class="mb-2"></vs-input>
                            </vs-col>
                            <vs-col vs-xs="12" vs-sm="6" vs-lg="6">
                                <vs-input v-model="models.user.meta.phone" label-placeholder="Contact No." class="mb-2"></vs-input>
                            </vs-col>
                        </vs-row>

                        <vs-row>
                            <vs-col vs-xs="12" vs-sm="6" vs-lg="6">
                                <vs-input v-model="models.user.password" label-placeholder="Password" type="password"></vs-input>
                            </vs-col>
                            <vs-col vs-xs="12" vs-sm="6" vs-lg="6">
                                <vs-input v-model="models.user.c_password" label-placeholder="Confirm Password" type="password"></vs-input>
                            </vs-col>
                        </vs-row>
                    </div>
                </vs-card>
            </vs-col>
            <vs-col vs-xs="12" vs-sm="5" vs-lg="3">
                <vs-card>
                    <div>
                        <vs-button @click="$route.params.id ? update() : create()" :disabled="!valid">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                        <vs-button @click="$router.push({ name: 'users' })" color="grey" class="float-right">Cancel</vs-button>
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
                models      : {
                    user        : {
                        role            : 'administrator',
                        meta            : {}
                    }
                }
            }
        },
        methods     : {
            create() {
                if( this.models.user.password != this.models.user.c_password )
                    this.$vs.notify({ title: 'Error', text: 'Passwords does not match.', color: 'danger' })
                else if( !this.models.user.employee_id )
                    this.$vs.notify({ title: 'Error', text: 'Please specify Employee ID.', color: 'danger' })
                else 
                    this.$store.dispatch('createDataBySource', { source: 'users', data: this.models.user }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'New '+ this.models.user.role +' created.', color: 'success' })

                        this.$router.push({ name: 'user_edit', params: { id: response.data.response.id }})
                    })
            },
            update() {
                
            }
        },
        computed    : {
            valid() {
                if( !this.models.user.role )
                    return false;

                if( !this.models.user.email )
                    return false;

                if( !this.models.user.password || !this.models.user.c_password ) 
                    return false;

                return true
            }
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'users', alias: 'advisers', filters: { key: 'role', value: 'adviser' } })

            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'users', id: this.$route.params.id }).then(response => {
                        
                    }).catch(_ => {
                        this.$router.push({ name: 'users' })
                    })
                } else {
                    
                }
            }
        }
    }
</script>