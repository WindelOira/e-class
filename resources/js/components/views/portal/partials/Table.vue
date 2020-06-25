<template>
    <div>
        <vs-table
            :max-items="20"
            pagination
            :data="data"
            description
            :description-title="title" 
            :noDataText="`No ${title.toLowerCase()} found.`"
            description-connector="of"
            description-body="Pages">
            <template slot="header">
                <div class="pt-2 pb-3">
                    <h3 class="mb-1">{{ title }}</h3>
                    <vs-button @click="getDatasBySource('published')" type="flat" class="py-1 px-2">Published</vs-button>
                    <span class="mx-1">|</span>
                    <vs-button @click="getDatasBySource('trashed')" color="danger" type="flat" class="py-1 px-2">Trashed</vs-button>
                </div>
            </template>
            <template slot="thead">
                <vs-th v-for="(th, th_key) in headers" :key="th_key">{{ th.text }}</vs-th>
                <vs-th width="150"></vs-th>
            </template>

            <template slot-scope="{data}">
                <vs-tr v-for="(td, td_key) in data" :key="td_key" >
                    <vs-td v-for="(th, th_key) in headers" :key="th_key">
                        <slot :name="th.key" v-bind:td="{ id: data[td_key].id, key: td_key, val: data[td_key][th.key] }">{{ data[td_key][th.key] }}</slot>
                    </vs-td>
                    <vs-td>
                        <vs-button v-if="data[td_key].deleted_at" @click="restoreDatasBySource(data[td_key].id)" type="flat">Restore</vs-button>
                        <vs-button v-else @click="getDataBySource(data[td_key].id)" type="flat">Edit</vs-button>

                        <vs-button @click="deleteDataBySource(data[td_key].id)" color="danger" type="flat">Delete</vs-button>
                    </vs-td>
                </vs-tr>
            </template>
        </vs-table>
    </div>
</template>

<script>
    export default {
        props       : ['title', 'source', 'alias', 'headers'],
        data() {
            return {
                mode    : 'published',
                data    : []
            }
        },
        methods     : {
            getDatasBySource(status = 'published') {
                this.mode = status
                this.$store.dispatch('getDatasBySource', { source: this.source, status: status }).then(response => {
                    this.data = response.data.response
                })
            },
            getDataBySource(id) {
                this.$store.dispatch('getDataBySource', { source: this.source, id: id }).then(response => {
                    this.$router.push({ path: '/dashboard/'+ (this.alias ? this.alias : this.source) +'/edit/'+ id })
                })
            },
            deleteDataBySource(id) {
                this.$store.dispatch('deleteDataBySource', { source: this.source, id: id }).then(response => {
                    this.$vs.notify({ title: 'Success', text: this.title.slice(0, -1).toLowerCase() +' deleted.', color: 'danger' })

                    this.getDatasBySource(this.mode)
                })
            },
            restoreDatasBySource(id) {
                this.$store.dispatch('restoreDatasBySource', { source: this.source, id: id }).then(response => {
                    this.$vs.notify({ title: 'Success', text: this.title.slice(0, -1).toLowerCase() +' restored.' })

                    this.getDatasBySource(this.mode)
                })
            }
        },
        mounted() {
            this.getDatasBySource()
        }
    }
</script>