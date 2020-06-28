<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'user_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" source="users" title="Accounts" :filters="true">
            <template v-slot:filters>
                <vs-select v-model="models.filters.role" @change="filter('role')" label="Filter by role:" placeholder="Select role">
                    <vs-select-item v-for="(role, indexr) in $store.state.options.roles" :key="indexr" :value="role.value" :text="role.text"></vs-select-item>
                </vs-select>
            </template>
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
                ],
                models      : {
                    filters     : {
                        role        : ''
                    }
                }
            }
        },
        methods     : {
            filter(type) {
                this.$store.dispatch('getDatasBySource', { source: 'users', status: 'published', filters: `${type}=${this.models.filters[type]}` })
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