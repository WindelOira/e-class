require('./bootstrap');

import 'vuesax/dist/vuesax.css'
import 'material-icons/iconfont/material-icons.css'
import 'CSS/bootstrap-4-utilities/bootstrap-4-utilities.min.css'
import 'vue2-timepicker/dist/VueTimepicker.css'
import 'vue-multiselect-listbox/dist/vue-multi-select-listbox.css'

import Vue from 'vue'
import Vuesax from 'vuesax'
import VueMoment from 'vue-moment'
import { extend } from 'vee-validate'
import { required, numeric, confirmed } from 'vee-validate/dist/rules'
import axios from 'JS/axios'

extend('required', {
    ...required,
    message     : 'This field is required'
})
extend('numeric', {
    ...numeric,
    message     : 'This field should contain numbers only.'
})
extend('confirmed', {
    ...confirmed,
    message     : 'Please confirm'
})

import Router from 'JS/router/router.js'
import Store from 'JS/store/index.js'
import App from 'JS/components/App.vue'

Vue.use(Vuesax, {
    theme   : {
        colors  : {
            primary : '#2E7D32',
            success : '#43A047',
            danger  : '#e53935',
            warning : '#FB8C00',
            dark    : '#212121',
            light   : '#fff'
        }
    }
})
Vue.use(VueMoment)

const app = new Vue({
    el          : '#app',
    components  : {
        App
    },
    store       : Store,
    router      : Router,
    render      : h => h(App)
})