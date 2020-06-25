<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'subject_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>
        
        <app-table :headers="headers" source="subjects" title="Subjects">
            <template v-slot:subject_track_id="subject_track">
                <router-link :to="{ name: 'subject-track_edit', params: { id: subject_track.td.val.id } }">{{ subject_track.td.val.name }}</router-link>
            </template>
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
                    { key: 'name', text: 'Subject' },
                    { key: 'subject_track_id', text: 'Track' },
                    { key: 'hours', text: 'Hours per Semester' }
                ]
            }
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'tracks' })
        }
    }
</script>