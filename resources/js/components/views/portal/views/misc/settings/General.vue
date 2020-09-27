<template>
    <vs-card>
        <div slot="header">
            <h4>General</h4>
        </div>
        <div>
            <validation-observer ref="observer" v-slot="{invalid}">
                <validation-provider rules="required" v-slot="{errors}">
                    <vs-input v-model="models.school_id" :danger="0 < errors.length" :danger-text="errors[0]" label="School ID" class="mb-3"></vs-input>
                </validation-provider>

                <validation-provider rules="required" v-slot="{errors}">
                    <vs-input v-model="models.school_name" :danger="0 < errors.length" :danger-text="errors[0]" label="School Name" class="mb-3"></vs-input>
                </validation-provider>

                <validation-provider rules="required" v-slot="{errors}">
                    <vs-input v-model="models.region" :danger="0 < errors.length" :danger-text="errors[0]" label="Region" class="mb-3"></vs-input>
                </validation-provider>

                <validation-provider rules="required" v-slot="{errors}">
                    <vs-input v-model="models.division" :danger="0 < errors.length" :danger-text="errors[0]" label="Division" class="mb-3"></vs-input>
                </validation-provider>

                <validation-provider rules="required" v-slot="{errors}">
                    <vs-input v-model="models.address" :danger="0 < errors.length" :danger-text="errors[0]" label="Address" class="mb-3"></vs-input>
                </validation-provider>

                <vs-button @click="submit" :disabled="invalid">Save</vs-button>
            </validation-observer>
        </div>
    </vs-card>
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
                models  : {}
            }
        },
        computed    : {
            ...mapGetters([
                'settings'
            ])
        },
        methods     : {
            submit() {
                this.$store.dispatch('updateSettings', this.models).then(response => {
                    this.$vs.notify({ title: 'Success', text: 'Settings updated.', color: 'success' })
                })
            }
        },
        created() {
            this.models.school_id = this.settings.school_id
            this.models.school_name = this.settings.school_name
            this.models.region = this.settings.region
            this.models.division = this.settings.division
            this.models.address = this.settings.address
        }
    }
</script>