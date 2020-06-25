<template>
    <div>
        <router-view></router-view>
        <app-footer></app-footer>
    </div>
</template>

<script>
    import Navbar from 'Partials/Navbar.vue'
    import Footer from 'Partials/Footer.vue'

    export default {
        components  : {
            'app-navbar'    : Navbar,
            'app-footer'    : Footer
        }
    }
</script>