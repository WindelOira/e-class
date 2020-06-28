<template>
    <div>
        <vs-row v-if="'administrator' == user.role" class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'class_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>
        
        <app-table :headers="headers" source="classes" title="Classes">
            <template v-slot:academic_year_id="academic_year">{{ academic_year.td.val.year - 1 }} - {{ academic_year.td.val.year }}</template>
            <template v-slot:subject_id="subject">{{ subject.td.val.name }}</template>
            <template v-slot:track_id="track">{{ track.td.val.name }}</template>
            <template v-slot:section_id="section">{{ section.td.val.name }}</template>
            <template v-slot:strand_id="strand">{{ strand.td.val.code }}</template>
            <template v-slot:semester="semester">{{ 1 == semester.td.val ? 'First' : 'Second' }} Semester</template>
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
            return  {
                headers     : [
                    { key: 'class_id', text: 'Class ID' },
                    { key: 'subject_id', text: 'Subject' },
                    { key: 'track_id', text: 'Track' },
                    { key: 'section_id', text: 'Section' },
                    { key: 'strand_id', text: 'Strand' },
                    { key: 'hours', text: 'Hours' },
                    { key: 'academic_year_id', text: 'Academic Year' },
                    { key: 'semester', text: 'Semester' },
                ]
            }
        },
        computed    : {
            ...mapGetters([
                'user'
            ])
        }
    }
</script>