<template>
    <div>
        <h3 class="mb-3">Settings</h3>

        <vs-row>
            <vs-col vs-lg="6" vs-md="6" vs-sm="12">
                <general></general>
            </vs-col>
            <vs-col vs-lg="6" vs-md="6" vs-sm="12">
                <system-status></system-status>
            </vs-col>
        </vs-row>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import General from 'Views/portal/views/misc/settings/General'
    import SystemStatus from 'Views/portal/views/misc/settings/SystemStatus'

    export default {
        components  : {
            'general'           : General,
            'system-status'     : SystemStatus
        },
        methods     : {
            ...mapGetters([
                'user'
            ])
        },
        computed    : {
            account     : {
                get() {
                    return this.user()
                }
            }
        },
        created() {
            if( this.account && 'administrator' != this.account.role ) {
                this.$router.push({ name: 'dashboard' })
            }
        }
    }
</script>