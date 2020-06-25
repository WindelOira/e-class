<template>
    <div>
        <vs-row class="mb-3">
            <vs-col vs-xs="12" vs-sm="12" vs-lg="6">
                <h3 class="my-1">Comments</h3>
            </vs-col>
            <vs-col vs-xs="12" vs-sm="12" vs-lg="6" class="text-right">
                <vs-button @click="pop('Add', true)" color="primary" type="filled">Add Comment</vs-button>
            </vs-col>
        </vs-row>

        <div v-if="0 < comments.length" class="comments-list mt-4">
            <vs-card v-for="(comment, indexc) in comments" :key="indexc">
                <div>
                    <div class="mb-2">
                        <vs-icon v-if="commenter.id == user.id" @click="remove(comment)" icon="delete" color="danger" size="14px" class="float-right"></vs-icon>
                        <vs-icon v-if="commenter.id == user.id" @click="pop('Edit', true, comment)" icon="edit" color="primary" size="14px" class="mr-2 float-right"></vs-icon>
                        <vs-avatar class="m-0 mr-2 float-left"/>
                        <h4>{{ comment.user_id.name }}</h4>
                        <small class="gray-text">{{ comment.created_at | moment('from', 'now', true) }} ago</small>
                    </div>
                    <blockquote>{{ comment.content }}</blockquote>
                </div>
            </vs-card>
        </div>
        <p v-else class="my-3 text-center">No comments found.</p>

        <vs-popup  :title="`${popup.mode} Comment`" :active.sync="popup.active">
            <vs-textarea v-model="models.comment.content"/>
            <vs-button @click="models.comment.id ? update() : create()">Save</vs-button>
        </vs-popup>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        props       : [ 'grading_sheet', 'commenter' ],
        data() {
            return {
                popup       : {
                    mode        : '',
                    active      : false
                },
                models      : {
                    comment         : {
                        content         : ''
                    }
                },
                comments    : []
            }
        },
        methods     : {
            pop(mode, active, comment = false) {
                this.popup.mode = mode
                this.popup.active = active
                this.models.comment = comment ? comment : { 
                    grading_sheet_id    : this.grading_sheet,
                    user_id             : this.commenter,
                    content             : '' 
                }
            },
            get() {
                axios({
                    method      : 'GET',
                    url         : `api/comments/${parseInt(this.grading_sheet)}/${this.commenter}`
                }).then(response => {
                    this.comments = response.data.response
                })
            },
            create() {
                this.$store.dispatch('createDataBySource', { source: 'comments', data: this.models.comment }).then(response => {
                    this.$vs.notify({ title: 'Success', text: 'Comment saved.', color: 'success' })

                    this.get()
                    this.pop('', false)
                })
            },
            update() {
                this.$store.dispatch('updateDataBySource', { source: 'comments', id: this.models.comment.id, data: this.models.comment }).then(response => {
                    this.$vs.notify({ title: 'Success', text: 'Comment updated.', color: 'success' })

                    this.get()
                    this.pop('', false)
                })
            },
            remove(comment) {
                this.$store.dispatch('deleteDataBySource', { source: 'comments', id: comment.id }).then(response => {
                    this.$vs.notify({ title: 'Success', text: 'Comment deleted.', color: 'success' })
                    
                    this.get()
                })
            }
        },
        computed    : {
            ...mapGetters([
                'user'
            ])
        },
        created() {
            this.get()

            this.models.comment.grading_sheet_id = parseInt(this.grading_sheet)
            this.models.comment.user_id = this.user
        }
    }
</script>