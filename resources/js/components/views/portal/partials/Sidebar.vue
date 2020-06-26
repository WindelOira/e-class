<template>
    <div id="app-sidebar" class="dark">
        <div v-for="(item, key) in items" :key="key" v-if="item.roles.indexOf(user.role) >= 0" class="vs-list p-0 mb-2">
            <div class="vs-list--header light primary-text px-3">{{ item.title }}</div>
            <div v-for="(subItem, subKey) in item.list" :key="subKey" v-if="subItem.roles.indexOf(user.role) >= 0" class="vs-list--item pr-3 pl-4" :class="{ 'vs-list--item-active': $route.name == subItem.name }">
                <div class="list-titles">
                    <router-link :to="{ name: subItem.name }" class="vs-list--title light-text">{{ subItem.title }}</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        data() {
            return {
                items   : [
                    {
                        title   : 'General',
                        list    : [
                            { title: 'Academic Years', name: 'academic-years', roles: ['administrator'] },
                            { title: 'Grade Levels', name: 'levels', roles: ['administrator'] },
                            { title: 'Strands', name: 'strands', roles: ['administrator'] },
                            { title: 'Tracks', name: 'tracks', roles: ['administrator'] },
                            { title: 'Classes', name: 'classes', roles: ['administrator', 'subject-teacher'] },
                            { title: 'Sections', name: 'sections', roles: ['administrator', 'adviser', 'subject-teacher'] },
                            { title: 'Subject', name: 'subjects', roles: ['administrator'] },
                            { title: 'Subject Tracks', name: 'subject-tracks', roles: ['administrator'] }
                        ],
                        roles   : [
                            'administrator', 'adviser', 'subject-teacher'
                        ]
                    },
                    {
                        title   : 'Record Management',
                        list    : [
                            { title: 'Attendance', name: 'attendances', roles: ['subject-teacher'] },
                            { title: 'Consolidated Grades', name: 'consolidated-grades', roles: ['chief-adviser'] },
                            { title: 'Grading Sheets', name: 'grading-sheets', roles: ['program-head', 'chief-adviser', 'subject-teacher'] },
                            { title: 'Computation Variables', name: 'computation-variables', roles: ['chief-adviser'] }
                        ],
                        roles   : [
                            'program-head', 'chief-adviser', 'subject-teacher'
                        ]
                    },
                    {
                        title   : 'User Management',
                        list    : [
                            { title: 'Accounts', name: 'users', roles: ['administrator'] },
                            { title: 'Students', name: 'students', roles: ['administrator', 'subject-teacher'] }
                        ],
                        roles   : [
                            'administrator', 'subject-teacher'
                        ]
                    }
                ]
            }
        },
        computed    : {
            ...mapGetters([
                'user'
            ])
        },
        created() {
            // this.$store.dispatch('checkAuth')
        }
    }
</script>