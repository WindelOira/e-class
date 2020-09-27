<template>
    <div>
        <vs-row v-if="isRole('administrator')" class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'section_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" source="sections" title="Sections">
            <template v-slot:user_id="adviser">{{ adviser.td.val ? adviser.td.val.name : '' }}</template>
            <template v-slot:strand_id="strand">{{ strand.td.val ? strand.td.val.code : '' }}</template>
            <template v-slot:level_id="level">{{ level.td.val ? level.td.val.name : '' }}</template>
            <template v-slot:academic_year_id="academic_year">
                <span v-if="academic_year.td.val">{{ academic_year.td.val.year }} - {{ academic_year.td.val.year + 1 }}</span>
            </template>
            <template v-if="isRole('teacher')" v-slot:consolidated_grades="section">
                <vs-button :to="{name: 'section_consolidated-grades', params: {id: section.td.id, semester: 'first'}}" type="flat" class="py-1 px-2">First Semester</vs-button>
                <span class="mx-1">|</span>
                <vs-button :to="{name: 'section_consolidated-grades', params: {id: section.td.id, semester: 'second'}}" type="flat" class="py-1 px-2">Second Semester</vs-button>
            </template>
        </app-table>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
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
                    { key: 'academic_year_id', text: 'Academic Year' }
                ]
            }
        },
        computed    : {
            ...mapGetters([
                'isRole'
            ])
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'strands' })
            this.$store.dispatch('getSelectOptions', { source: 'levels' })
            // this.$store.dispatch('getSelectOptions', { source: 'levels' })
            this.$store.dispatch('getSelectOptions', { source: 'users', alias: 'advisers', filters: [{ key: 'role', value: 'teacher' }] })

            if( this.isRole('teacher') ) {
                this.headers.push({ key: 'consolidated_grades', text: 'Consolidated Grades'})
            }
        }
    }
</script>