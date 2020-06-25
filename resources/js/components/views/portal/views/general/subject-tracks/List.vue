<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'subject-track_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" source="subject_tracks" alias="subject-tracks" title="Subject Tracks"></app-table>
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
                    { key: 'name', text: 'Track' },
                    { key: 'written_work', text: 'Written Work' },
                    { key: 'performance_task', text: 'Performance Task' },
                    { key: 'quarterly_assessment', text: 'Quarterly Assessment' }
                ]
            }
        }
    }
</script>