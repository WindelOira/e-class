import Vue from 'vue'
import VueRouter from 'vue-router'

import store from 'JS/store/index.js'
import routes from 'JS/router/routes.js'

Vue.use(VueRouter)

const router = new VueRouter({
    routes  : routes,
})

router.beforeEach((to, from, next) => {
    store.commit('SET_TEMPLATE_OPTION', {key: 'isLoading', value: true})
    store.commit('SET_TEMPLATE_OPTION', {key: 'noSidebar', value: false})

    if( to.matched.some( record => record.meta.requiresAuth ) ) {
        store.dispatch('checkAuth').then(() => {
            next()
        }).catch(() => {
            next({
                path    : '/'
            })
        })

        next()
    } else {
        next()
    }
})

export default router