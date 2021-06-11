export default {
    namespaced: 'colors',
    state: {
        colors: {
            known: null,
            study: null,
            unknown: null
        },
        isChange: null
    },
    mutations: {
        setColor(state, {name, color}) {
            state.colors[name] = color
        }
    },
    getters: {
        getColorGetter: state => name => state.colors[name]
    }
}