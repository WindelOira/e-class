const getters = {
    user        : state => {
        return state.user.data
    },
    template    : state => {
        return state.template
    },
    apiData     : state => {
        return state.apiData
    }
}

export default getters