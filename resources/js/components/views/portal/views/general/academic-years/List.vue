<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'academic-year_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>
        
        <app-table :headers="headers" source="academic_years" title="Academic Years"></app-table>
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