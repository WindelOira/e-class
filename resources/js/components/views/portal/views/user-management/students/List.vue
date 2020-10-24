<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'student_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" :filters="true" source="students" title="Students">
            <template v-slot:filters>
                <vs-select v-model="models.filters.strand_id" @change="filter('strand_id')" label="Filter by strand:" placeholder="Select strand">
                    <vs-select-item v-for="(strand, indexs) in $store.state.options.strands" :key="indexs" :value="strand.value" :text="strand.text"></vs-select-item>
                </vs-select>
            </template>
            <template v-slot:strand_id="strand">{{ strand.td.val ? strand.td.val.code : '' }}</template>
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
                    { key: 'student_number', text: 'Student LRN' },
                    { key: 'strand_id', text: 'Strand' },
                    { key: 'name', text: 'Name' },
                ],
                models      : {
                    filters         : {
                        strand_id       : ''
                    }
                }
            }
        },
        computed    : {
            ...mapGetters(['user'])
        },
        methods     : {
            filter(type) {
                this.$store.dispatch('getDatasBySource', { source: 'students', status: 'published', filters: `${type}=${this.models.filters[type]}` })
            }
        },
        mounted() {
            this.$store.dispatch('getSelectOptions', { source: 'strands' })

            if( this.$route.params.section_id ) {
                this.$store.dispatch('getDatasBySource', { source: 'students', status: 'published', filters: `section_id=${this.$route.params.section_id}` })
            } else if( this.user.section ) {
                let section_ids = []
                this.user.section.forEach(section => {
                    section_ids.push(section.id)
                })

                this.$store.dispatch('getDatasBySource', { source: 'students', status: 'published', filters: `section_id=${section_ids.join('-')}` })
            }
        }
    }
</script>