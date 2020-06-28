<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'student_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" source="students" title="Students">
            <template v-slot:strand_id="strand">{{ strand.td.val.code }}</template>
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
                    { key: 'student_number', text: 'Student LRN' },
                    { key: 'strand_id', text: 'Strand' },
                    { key: 'name', text: 'Name' },
                ]
            }
        }
    }
</script>