<template>
    <div>
        <vs-row v-if="'administrator' == user.role" class="mb-3">
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
            <template v-slot:students_section_id="students_section_id">
                <router-link :to="{ name: 'students_by_section', params: { section_id: students_section_id.td.val } }">View</router-link>
            </template>
            <template v-if="'teacher' == user.role" v-slot:actions>
                <span></span>
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
                    { key: 'academic_year_id', text: 'Academic Year' },
                    { key: 'students_section_id', text: 'Students' }
                ]
            }
        },
        computed    : {
            ...mapGetters([
                'user'
            ])
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'strands' })
            this.$store.dispatch('getSelectOptions', { source: 'levels' })
            // this.$store.dispatch('getSelectOptions', { source: 'levels' })
            this.$store.dispatch('getSelectOptions', { source: 'users', alias: 'advisers', filters: [{ key: 'role', value: 'teacher' }] })
        }
    }
</script>