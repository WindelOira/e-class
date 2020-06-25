<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'user_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" source="users" title="Accounts">
            <template v-slot:role="role">{{ roles[role.td.val] }}</template>
        </app-table>
    </div>
</template>

<script>
    import Table from 'Views/portal/partials/Table.vue'

    export default {
        components  : {
            'app-table'     : Table
        },
        data() {
            return {
                headers     : [
                    { key: 'employee_id', text: 'Employee ID' },
                    { key: 'role', text: 'Role' },
                    { key: 'name', text: 'Name' },
                    { key: 'email', text: 'Email Address' }
                ]
            }
        },
        computed    : {
            roles() {
                let roles = []

                this.$store.state.options.roles.forEach(r => {
                    roles[r.value] = r.text
                })

                return roles
            }
        }
    }
</script>