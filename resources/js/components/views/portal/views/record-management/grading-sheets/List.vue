<template>
    <div>
        <vs-row v-if="'teacher' == user.role" class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'grading-sheet_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" source="grading_sheets" alias="grading-sheets" title="Grading Sheets">
            <template v-slot:user_id="adviser">{{ adviser.td.val ? adviser.td.val.name : '' }}</template>
            <template v-slot:subject_id="subject">{{ subject.td.val ? subject.td.val.name : '' }}</template>
            <template v-slot:section_id="section">{{ section.td.val ? section.td.val.name : '' }}</template>
            <template v-slot:level_id="level">{{ level.td.val ? level.td.val.name : '' }}</template>
            <template v-slot:subject_track_id="subject_track">{{ subject_track.td.val ? subject_track.td.val.name : '' }}</template>
        </app-table>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import Table from 'Views/portal/partials/Table.vue'

    export default {
        components  : {
            'app-table'     : Table
        },
        data() {
            return {
                headers         : [
                    { key: 'user_id', text: 'Adviser' },
                    { key: 'subject_id', text: 'Subject' },
                    { key: 'section_id', text: 'Section' },
                    { key: 'level_id', text: 'Level' },
                    { key: 'subject_track_id', text: 'Subject Track' },
                    // { key: 'year', text: 'School Year' },
                ],
                attendance      : false
            }
        },
        computed    : {
            ...mapGetters([
                'user'
            ])
        }
    }
</script>