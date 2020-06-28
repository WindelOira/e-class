import axios from 'axios'
import state from 'JS/store/states.js'

const actions = {
    checkAuth({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method  : 'GET',
                url     : `api/user`
            }).then(response => {
                commit('SET_TEMPLATE_OPTION', {key: 'isLoading', value: false})
                commit('SET_USER', response.data.response)

                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    login({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method  : 'POST',
                url     : `api/login`,
                data    : payload     
            }).then(response => {
                localStorage.setItem('eclass_token', response.data.response.token)
                window.axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('eclass_token')

                commit('SET_USER', response.data.response.user)
                commit('SET_TOKEN', response.data.response.token)
                
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    logout({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method  : 'POST',
                url     : `api/logout`,
                data    : payload
            }).then(response => {
                localStorage.removeItem('eclass_token')

                commit('UNSET_TOKEN')
                commit('UNSET_USER')

                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },

    // API actions
    getDatasBySource({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method      : 'GET',
                url         : payload.filters ? `api/${payload.source}/${payload.status}/${payload.filters}` : `api/${payload.source}/${payload.status}`
            }).then(response => {
                if( !payload.no_commit ) {
                    commit('UNSET_ACTIVE_DATA')
                    commit('SET_DATAS', { source: payload.source, datas: response.data.response })
                }

                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    getDataBySource({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method      : 'GET',
                url         : `api/${payload.source}/${payload.id}`
            }).then(response => {
                if( !payload.no_commit ) {
                    commit('SET_ACTIVE_DATA', { source: payload.source, active: response.data.response })
                }

                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    createDataBySource({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method      : 'POST',
                url         : `api/${payload.source}`,
                data        : payload.data
            }).then(response => {
                if( !payload.no_commit ) {
                    commit('ADD_DATA', { source: payload.source, data: response.data.response })
                    commit('SET_ACTIVE_DATA', { source: payload.source, active: response.data.response })
                }

                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    updateDataBySource({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method      : 'PATCH',
                url         : `api/${payload.source}/${payload.id}`,
                data        : payload.data
            }).then(response => {
                if( !payload.no_commit ) {
                    commit('SET_ACTIVE_DATA', { source: payload.source, active: response.data.response })
                }

                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    deleteDataBySource({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method      : 'DELETE',
                url         : `api/${payload.source}/${payload.id}`
            }).then(response => {
                if( !payload.no_commit ) {
                    commit('UNSET_ACTIVE_DATA')
                }

                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    restoreDatasBySource({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method      : 'PATCH',
                url         : `api/${payload.source}/${payload.id}/restore`
            }).then(response => {
                if( !payload.no_commit ) {
                    commit('SET_ACTIVE_DATA', { source: payload.source, active: response.data.response })
                }

                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },

    // Select options actions
    getSelectOptions({ commit }, payload) {
        if( 0 < state.options[payload.alias ? payload.alias : payload.source].length )
            return;

        return new Promise((resolve, reject) => {
            axios({
                method          : 'GET',
                url             : `api/${payload.source}/published`
            }).then(response => {
                if( payload.filters ) {
                    commit('SET_OPTIONS', { key: payload.alias ? payload.alias : payload.source, options: response.data.response, filters: payload.filters })
                } else {
                    commit('SET_OPTIONS', { key: payload.alias ? payload.alias : payload.source, options: response.data.response })
                }

                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },


    // Users
    getUsers({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method      : 'GET',
                url         : `api/users/published`,
                // headers   `: {
                //     'Authorization' : 'Bearer '+ $store
                // }
            }).then(response => {
                if( !payload.no_commit ) {
                    commit('GET_USERS', response)
                }
                
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    getUser({ commit }, payload) {
        return new Promise((resolve, reject) => {
            axios({
                method      : 'GET',
                url         : 'api/users/'+ payload.id
            }).then(response => {
                commit('GET_USER', response)

                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    setUser({ commit }, payload) {
        commit('SET_USER', payload)
    }
}

export default actions