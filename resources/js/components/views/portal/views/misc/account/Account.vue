<template>
    <div>
        <validation-observer ref="observer">
            <vs-row>
                <vs-col vs-xs="12" vs-sm="2" vs-lg="3"></vs-col>
                <vs-col vs-xs="12" vs-sm="8" vs-lg="6">
                    <vs-card>
                        <div slot="header">
                            <h3>Update Profile</h3>
                        </div>
                        <div>
                            <vs-input v-model="account.employee_id" label="Employee ID" class="mb-3" disabled></vs-input>

                            <validation-provider rules="required" v-slot="{errors}">
                                <vs-input v-model="account.email" :danger="0 < errors.length" :danger-text="errors[0]" label="Email address" class="mb-3"></vs-input>
                            </validation-provider>

                            <vs-row>
                                <vs-col vs-xs="12" vs-sm="12" vs-lg="6">
                                    <vs-input v-model="account.metas.fname" label="First Name" class="mb-3"></vs-input>
                                </vs-col>
                                <vs-col vs-xs="12" vs-sm="12" vs-lg="6">
                                    <vs-input v-model="account.metas.lname" label="Last Name" class="mb-3"></vs-input>
                                </vs-col>
                            </vs-row>

                            <vs-divider class="mt-0"></vs-divider>

                            <vs-input v-model="account.opassword" label="Old Password" type="password" class="mb-3"></vs-input>
                            <vs-row>
                                <vs-col vs-xs="12" vs-sm="12" vs-lg="6">
                                    <vs-input v-model="account.password" label="New Password" type="password" class="mb-3"></vs-input>
                                </vs-col>
                                <vs-col vs-xs="12" vs-sm="12" vs-lg="6">
                                    <vs-input v-model="account.cpassword" label="Confirm New Password" type="password" class="mb-3"></vs-input>
                                </vs-col>
                            </vs-row>

                            <vs-button @click="update" :disabled="!valid || (0 == settings.status)">Update</vs-button>
                        </div>
                    </vs-card>
                </vs-col>
                <vs-col vs-xs="12" vs-sm="2" vs-lg="3"></vs-col>
            </vs-row>
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
        methods     : {
            ...mapGetters([
                'user', 'settings'
            ]),
            update() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;
                    
                    this.$store.dispatch('updateDataBySource', { source: 'users', id: this.account.id, data: this.account }).then(response => {
                        this.$vs.notify({ title: 'Success', text: 'Account updated.', color: 'success' })

                        this.$store.dispatch('setUser', response.data.response)
                    }).catch(error => {
                        this.$vs.notify({ title: 'Error', text: error.response.data.response, color: 'danger' })
                    })
                })
            }
        },
        computed    : {
            account    : {
                get() {
                    return this.user()
                }
            },
            valid() {
                if( this.account.opassword && (!this.account.password || !this.account.cpassword) )
                    return false;

                if( this.account.password != this.account.cpassword )
                    return false;

                return true
            }
        }
    }
</script>