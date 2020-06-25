<template>
    <div>
        <vs-row class="mb-3">
            <vs-col>
                <vs-button @click="$router.push({ name: 'attendance_new' })">Add New</vs-button>
            </vs-col>
        </vs-row>

        <app-table :headers="headers" source="attendances" title="Attendance">
            <template v-slot:students="students">
                <vs-button @click="view(students.td.id)" type="flat">View</vs-button>
            </template>
        </app-table>

        <vs-popup fullscreen title="Attendance" :active.sync="attendance">
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </p>
        </vs-popup>
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
                headers         : [
                    { key: 'date_time', text: 'Date/Time' },
                    { key: 'students', text: 'Students' },
                ],
                attendance      : false
            }
        },
        methods     : {
            view(id) {
            //    this.$store.dispatch() 

                this.attendance = true
                console.log(id)
            }
        }
    }
</script>