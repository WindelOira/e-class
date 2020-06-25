<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'strand_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" source="strands" title="Strands"></app-table>
    </div>
</template>

<script>
    import Table from 'Views/portal/partials/Table.vue'

    export default {
        components      : {
            'app-table'     : Table
        },
        data() {
            return {
                headers     : [
                    { key: 'code', text: 'Code' },
                    { key: 'description', text: 'Description' }
                ]
            }
        }
    }
</script>