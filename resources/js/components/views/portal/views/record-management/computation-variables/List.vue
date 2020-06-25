<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'computation-variables_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" source="computation-variables" title="Computation Variables"></app-table>
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
                headers         : [
                    { key: 'name', text: 'Name' },
                    { key: 'percentage', text: 'Percentage' },
                ],
                attendance      : false
            }
        }
    }
</script>