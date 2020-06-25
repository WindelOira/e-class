<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'class_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>
        
        <app-table :headers="headers" source="classes" title="Classes">
            <template v-slot:academic_year_id="academic_year">
                <router-link :to="{ name: 'academic-year_edit', params : { id : academic_year.td.val.id } }">{{ academic_year.td.val.year - 1 }} - {{ academic_year.td.val.year }}</router-link>
            </template>
            <template v-slot:subject_id="subject">
                <router-link :to="{ name: 'subject_edit', params : { id : subject.td.val.id } }">{{ subject.td.val.name }}</router-link>
            </template>
            <template v-slot:track_id="track">
                <router-link :to="{ name: 'track_edit', params : { id : track.td.val.id } }">{{ track.td.val.name }}</router-link>
            </template>
            <template v-slot:section_id="section">
                <router-link :to="{ name: 'level_edit', params : { id : section.td.val.id } }">{{ section.td.val.name }}</router-link>
            </template>
            <template v-slot:strand_id="strand">
                <router-link :to="{ name: 'strand_edit', params : { id : strand.td.val.id } }">{{ strand.td.val.code }}</router-link>
            </template>
            <template v-slot:semester="semester">
                {{ 1 == semester.td.val ? 'First' : 'Second' }} Semester
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
        }
    }
</script>