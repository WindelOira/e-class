const getters = {
    user        : state => {
        return state.user.data
    },
    template    : state => {
        return state.template
    },
    apiData     : state => {
        return state.apiData
    },
    options     : state => {
        return state.options
    }
}

export default getters