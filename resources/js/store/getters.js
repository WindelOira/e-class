const getters = {
    user        : state => {
        return state.user.data
    },
    template    : state => {
        return state.template
    }
}

export default getters