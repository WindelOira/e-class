<template>
    <div id="app-dashboard" :class="{'no-sidebar': template.noSidebar}">
        <portal-sidebar v-if="user && !template.noSidebar"></portal-sidebar>
        <div id="app-main" class="px-3 py-4">
            <portal-navbar v-if="user"></portal-navbar>

            <div id="app-content" class="py-4 mt-4">
                <vs-progress v-if="template.isLoading" indeterminate color="primary">primary</vs-progress>
                <router-view v-else></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import Navbar from 'Views/portal/partials/Navbar.vue'
    import Sidebar from 'Views/portal/partials/Sidebar.vue'

    export default {
        components  : {
            'portal-navbar'     : Navbar,
            'portal-sidebar'    : Sidebar
        },
        computed    : {
            ...mapGetters([
                'template', 'user'
            ])
        }
    }
</script>