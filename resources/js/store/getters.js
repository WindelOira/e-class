const getters = {
    auth(state) {
        return state.user.token
    },
    user(state) {
        return state.user.data
    },
    isRole(state) {
        return (value) => {
            return value == state.user.data.role
        }
    },
    template(state) {
        return state.template
    },
    apiData(state) {
        return state.apiData
    },
    options(state) {
        return state.options
    },
    settings(state) {
        var settings = {}

        state.settings.forEach(setting => {
            settings[setting.key] = setting.value
        })

        return settings
    }
}

export default getters