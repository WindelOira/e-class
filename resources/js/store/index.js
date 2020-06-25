import Vue from 'vue'
import Vuex from 'vuex'

import states from 'JS/store/states.js'
import mutations from 'JS/store/mutations.js'
import actions from 'JS/store/actions.js'
import getters from 'JS/store/getters.js'

Vue.use(Vuex)

const store = new Vuex.Store({
    state       : states,
    mutations   : mutations,
    actions     : actions,
    getters     : getters
})

export default store