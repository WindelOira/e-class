import Vue from 'vue'

const mutations = {
    SET_TOKEN(state, payload) {
        state.user.token = payload
    },
    UNSET_TOKEN(state) {
        state.user.token = null
    },
    SET_USER(state, payload) {
        state.user.data = payload
    },
    UNSET_USER(state) {
        state.user.data = null
    },

    // Template commits
    SET_TEMPLATE_OPTION(state, payload) {
        state.template[payload.key] = payload.value
    },

    // API commits
    SET_DATAS(state, payload) {
        state.apiData.source = payload.source
        state.apiData.datas = payload.datas
    },
    ADD_DATA(state, payload) {
        if( state.apiData.source != payload.source )
            return;

        state.apiData.datas.push(payload.data)
    },
    UPDATE_DATA(state, payload) {
        if( state.apiData.source != payload.source )
            return;
        
    },
    DELETE_DATA(state, payload) {
        if( state.apiData.source != payload.source )
            return;
        
    },
    SET_ACTIVE_DATA(state, payload) {
        state.apiData.source = payload.source
        state.apiData.active = payload.active
    },
    UNSET_ACTIVE_DATA(state) {
        state.apiData.source = ''
        state.apiData.active = null
    },

    // Options commits
    SET_OPTIONS(state, payload) {
        if( payload.filters ) {
            payload.filters.forEach(filter => {
                payload.options = payload.options.filter(option => {
                    return option[filter.key] == filter.value
                })
            })
        }

        state.options[payload.key] = []
        payload.options.forEach(option => { 
            if( typeof option === 'undefined' ) 
                return false;
                
            if( state.options[payload.key].indexOf(option) < 0 ) {
                state.options[payload.key].push({
                    text        : option.name ? option.name : (option.code ? option.code : option.year),
                    value       : option.id,
                    selected    : 'undefined' == option.selected ? false : option.selected,
                    full        : option
                })
            }
        })
    },

    // Settings commits
    SET_SETTINGS(state, payload) {
        state.settings = payload
    },
    UNSET_SETTINGS(state) {
        state.settings = {}
    }
}

export default mutations