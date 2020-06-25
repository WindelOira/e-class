<template>
    <div>
        <h3 class="mb-3">Settings</h3>
        <vs-tabs>
            <vs-tab label="General">
                <div></div>
            </vs-tab>
            <vs-tab label="System Status">
                <div></div>
            </vs-tab>
        </vs-tabs>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
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
            if( 'administrator' != this.account.role ) {
                this.$router.push({ name: 'dashboard' })
            }
        }
    }
</script>