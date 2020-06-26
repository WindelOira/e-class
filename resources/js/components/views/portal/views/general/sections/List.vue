<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'section_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" source="sections" title="Sections">
            <template v-slot:user_id="adviser">{{ adviser.td.val.name }}</template>
            <template v-slot:strand_id="strand">{{ strand.td.val.code }}</template>
            <template v-slot:level_id="level">{{ level.td.val.name }}</template>
            <template v-slot:academic_year_id="academic_year">{{ academic_year.td.val.year - 1 }} - {{ academic_year.td.val.year }}</template>
            <template v-slot:consolidated_grades="section">
                <router-link :to="{ name: 'section_consolidated-grades', params: { id: section.td.id, semester: 'first' } }">First Semester</router-link>
                |
                <router-link :to="{ name: 'section_consolidated-grades', params: { id: section.td.id, semester: 'second' } }">Second Semester</router-link>
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
            return {
                headers     : [
                    { key: 'name', text: 'Name' },
                    { key: 'user_id', text: 'Adviser' },
                    { key: 'strand_id', text: 'Strand' },
                    { key: 'level_id', text: 'Level' },
                    { key: 'academic_year_id', text: 'Academic Year' },
                    { key: 'students', text: 'Students' },
                    { key: 'consolidated_grades', text: 'Consolidated Grades' }
                ]
            }
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'strands' })
            this.$store.dispatch('getSelectOptions', { source: 'levels' })
            // this.$store.dispatch('getSelectOptions', { source: 'levels' })
            this.$store.dispatch('getSelectOptions', { source: 'users', alias: 'advisers', filters: [{ key: 'role', value: 'adviser' }] })
        }
    }
</script>