<template>
    <vs-card>
        <div slot="header">
            <h4>System Status</h4>
        </div>
        <div>
            <vs-switch v-model="models.status" @click="updateStatus">
                <span slot="on">On</span>
                <span slot="off">Off</span>
            </vs-switch>
        </div>
    </vs-card>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        data() {
            return {
                models  : {
                    status  : false
                }
            }
        },
        computed    : {
            ...mapGetters([
                'settings'
            ])
        },
        methods     : {
            updateStatus() {
                this.$store.dispatch('updateSettings', this.models).then(response => {
                    this.$vs.notify({ title: 'Success', text: 'Settings updated.', color: 'success' })
                })
            }
        },
        created() {
            this.models.status = (1 == this.settings.status) ? true : false
        }
    }
</script>