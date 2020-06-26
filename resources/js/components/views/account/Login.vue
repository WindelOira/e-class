<template>
    <div class="container login">
        <validation-observer ref="observer">
            <vs-row>
                <vs-col vs-xs="12" vs-sm="3" vs-lg="4"></vs-col>
                <vs-col vs-xs="12" vs-sm="6" vs-lg="4">
                    <div class="text-center">
                        <img src="img/app/logo.png" alt="" class="login-logo mb-4">
                    </div>
                    <vs-card>
                        <div slot="header">
                            <h3 class="primary-text">Login</h3>
                        </div>
                        <div>
                            <form @submit.prevent="login">
                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-input v-model="models.employee_id" :danger="0 < errors.length" danger-text="Enter your employee ID." label="Employee ID" class="mb-3"></vs-input>
                                </validation-provider>

                                <validation-provider rules="required" v-slot="{errors}">
                                    <vs-input v-model="models.password" :danger="0 < errors.length" danger-text="Enter your password." label="Password" type="password" class="mb-3"></vs-input>
                                </validation-provider>

                                <vs-button button="submit">Login</vs-button>
                            </form>
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
                    employee_id     : null,
                    password        : null
                }
            }
        },
        methods     : {
            login() {
                this.$refs.observer.validate().then(success => {
                    if( !success )
                        return false;

                    this.$store.dispatch('login', this.models).then(response => {
                        this.$router.push({ name: 'dashboard' })
                    }).catch(error => {
                        this.$vs.notify({ title: 'Login failed', text: 'Please make sure you entered correct credentials.', color: 'danger' })
                    })
                })
            }
        }
    }
</script>