<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'academic-year_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>
        
        <app-table :headers="headers" source="academic_years" alias="academic-years" title="Academic Years">
            <template v-slot:year="year">
                <span v-if="year.td.val">{{ year.td.val }} - {{ year.td.val + 1 }}</span>
            </template>
        </app-table>
    </div>
</template>

<script>
    import Table from 'Views/portal/partials/Table.vue'

    export default {
        components      : {
            'app-table'     : Table
        },
        data() {
            return  {
                headers     : [
                    { key: 'year', text: 'Academic Year' },
                ]
            }
        }
    }
</script>