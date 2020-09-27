<template>
    <vs-navbar class="nabarx p-2">
        <div slot="title">
            <vs-navbar-title>
                <router-link :to="{ name: 'dashboard' }" class="vs-navbar--logo">
                    <img src="img/app/logo.png" alt="">
                </router-link>
                Hello <span>{{ user.meta ? `${user.meta.fname} ${user.meta.lname}` : user.name }}</span>
            </vs-navbar-title>
        </div>
        
        <vs-navbar-item v-if="'administrator' == user.role" index="0">
            <router-link :to="{ name: 'settings' }">Settings</router-link>
        </vs-navbar-item>
        <vs-navbar-item v-if="!isRole('administrator')" index="1">
            <router-link :to="{ name: 'account' }">Account</router-link>
        </vs-navbar-item>
        <vs-navbar-item index="2">
            <a @click="logout" href="javascript:;">Logout</a>
        </vs-navbar-item>
    </vs-navbar>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        methods     : {
            logout() {
                this.$store.dispatch('logout').then(response => {
                    this.$router.push({ name: 'login' })
                })
            }
        },
        computed    : {
            ...mapGetters([
                'user', 'isRole'
            ])
        }
    }
</script>