<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'subject_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>
        
        <app-table :headers="headers" source="subjects" title="Subjects" :filters="true">
            <template v-slot:filters>
                <vs-row>
                    <vs-col vs-xs="6" vs-sm="6" vs-lg="6">
                        <vs-select v-model="models.filters.strand_id" @change="filter('strand_id')" label="Filter by strand:" placeholder="Select strand">
                            <vs-select-item v-for="(strand, indexs) in options.strands" :key="indexs" :value="strand.value" :text="strand.text"></vs-select-item>
                        </vs-select>
                    </vs-col>

                    <vs-col vs-xs="6" vs-sm="6" vs-lg="6">
                        <vs-select v-model="models.filters.semester" @change="filter('semester')" label="Filter by semester:" placeholder="Select semester">
                            <vs-select-item value="first" text="First semester"></vs-select-item>
                            <vs-select-item value="second" text="Second semester"></vs-select-item>
                        </vs-select>
                    </vs-col>
                </vs-row>
            </template>
            <template v-slot:subject_track_id="subject_track">{{ subject_track.td.val ? subject_track.td.val.name : '' }}</template>
            <template v-slot:strand_id="strand">{{ strand.td.val ? strand.td.val.code : '' }}</template>
            <template v-slot:semester="semester">
                <span v-if="semester.td.val">{{ semester.td.val[0].toUpperCase() }}{{ semester.td.val.substr(1) }}</span>
            </template>
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
                headers     : [
                    { key: 'name', text: 'Subject' },
                    { key: 'subject_track_id', text: 'Subject Track' },
                    { key: 'strand_id', text: 'Strand' },
                    { key: 'hours', text: 'Hours per Semester' },
                    { key: 'semester', text: 'Semester' }
                ],
                models      : {
                    filters     : {
                        semester    : '',
                        strand_id   : ''
                    }
                }
            }
        },
        methods     : {
            filter(type) {
                this.$store.dispatch('getDatasBySource', { source: 'subjects', status: 'published', filters: `${type}=${this.models.filters[type]}` })
            }
        },
        computed    : {
            ...mapGetters([
                'options'
            ])
        },
        created() {
            this.$store.dispatch('getSelectOptions', { source: 'strands' })
        }
    }
</script>