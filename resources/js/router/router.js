import Vue from 'vue'
import VueRouter from 'vue-router'

import store from 'JS/store/index.js'
import routes from 'JS/router/routes.js'

Vue.use(VueRouter)

const router = new VueRouter({
    routes  : routes,
})

router.beforeEach((to, from, next) => {
    if( to.matched.some( record => record.meta.requiresAuth ) ) {
        if( !store.getters.auth ) {
            return next({ 
                name  : 'login',
                query : {
                    redirect  : to.fullPath
                }
            })
        }
    }

    next()
})

export default router