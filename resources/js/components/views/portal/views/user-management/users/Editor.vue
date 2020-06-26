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

        <validation-observer ref="observer">
            <form @submit.prevent="$route.params.id ? update() : create()">
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
                                        <validation-provider rules="required" v-slot="{errors}">
                                            <vs-input v-model="models.user.employee_id" :danger="0 < errors.length" :danger-text="errors[0]" label-placeholder="Employee ID" class="mb-2"></vs-input>
                                        </validation-provider>
                                    </vs-col>
                                </vs-row>

                                <vs-row>
                                    <vs-col vs-xs="12" vs-sm="12" vs-lg="4">
                                        <validation-provider rules="required" v-slot="{errors}">
                                            <vs-input v-model="models.user.metas.fname" :danger="0 < errors.length" :danger-text="errors[0]" label-placeholder="First Name" class="mb-3"></vs-input>
                                        </validation-provider>
                                    </vs-col>
                                    <vs-col vs-xs="12" vs-sm="12" vs-lg="4">
                                        <validation-provider rules="required" v-slot="{errors}">
                                            <vs-input v-model="models.user.metas.mname" :danger="0 < errors.length" :danger-text="errors[0]" label-placeholder="Middle Name" class="mb-3"></vs-input>
                                        </validation-provider>
                                    </vs-col>
                                    <vs-col vs-xs="12" vs-sm="12" vs-lg="4">
                                        <validation-provider rules="required" v-slot="{errors}">
                                            <vs-input v-model="models.user.metas.lname" :danger="0 < errors.length" :danger-text="errors[0]" label-placeholder="Last Name" class="mb-3"></vs-input>
                                        </validation-provider>
                                    </vs-col>
                                </vs-row>

                                <vs-row>
                                    <vs-col>
                                        <vs-textarea v-model="models.user.metas.address" label="Address" class="mb-0"></vs-textarea>
                                    </vs-col>
                                </vs-row>

                                <vs-divider/>

                                <h4 class="mb-2">Login Details</h4>

                                <vs-row>
                                    <vs-col vs-xs="12" vs-sm="6" vs-lg="6">
                                        <validation-provider rules="required" v-slot="{errors}">
                                            <vs-input v-model="models.user.email" :danger="0 < errors.length" :danger-text="errors[0]" label-placeholder="Email Address" type="email" class="mb-2"></vs-input>
                                        </validation-provider>
                                    </vs-col>
                                    <vs-col vs-xs="12" vs-sm="6" vs-lg="6">
                                        <validation-provider rules="required" v-slot="{errors}">
                                            <vs-input v-model="models.user.metas.phone" :danger="0 < errors.length" :danger-text="errors[0]" label-placeholder="Contact No." class="mb-2"></vs-input>
                                        </validation-provider>
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
                                <vs-button button="submit">{{ $route.params.id ? 'Update' : 'Save' }}</vs-button>
                                <vs-button @click="$router.push({ name: 'users' })" color="grey" class="float-right">Cancel</vs-button>
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
        components      : {
            'validation-observer'   : ValidationObserver,
            'validation-provider'   : ValidationProvider
        },
        data() {
            return {
                models      : {
                    user        : {
                        role            : 'administrator',
                        metas           : {}
                    }
                }
            }
        },
        methods     : {
            create() {
                if( this.models.user.password != this.models.user.c_password )
                    this.$vs.notify({ title: 'Error', text: 'Passwords does not match.', color: 'danger' })
                else 
                    this.$refs.observer.validate().then(success => {
                        if( !success )
                            return false;

                        this.$store.dispatch('createDataBySource', { source: 'users', data: this.models.user }).then(response => {
                            this.$vs.notify({ title: 'Success', text: 'New '+ this.models.user.role +' created.', color: 'success' })

                            this.$router.push({ name: 'user_edit', params: { id: response.data.response.id }})
                        })
                    })
            },
            update() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('updateDataBySource', { source: 'users', id: this.models.user.id, data: this.models.user }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Account updated.', color: 'success' })
                    })
                })
            }
        },
        created() {
            if( this.$route.params.id ) {
                if( !this.$store.state.apiData.active ) {
                    this.$store.dispatch('getDataBySource', { source: 'users', id: this.$route.params.id }).then(response => {
                        this.models.user = response.data.response
                    }).catch(_ => {
                        this.$router.push({ name: 'users' })
                    })
                } else {
                    this.models.user = this.$store.state.apiData.active
                }
            }
        }
    }
</script>