import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(VueAxios, axios)

const instance = axios.create({})

const key = localStorage.getItem('eclass_token') || ''
if( key ) {
    instance.defaults.headers.common['Authorization'] = `Bearer ${key}`
}

export default instance