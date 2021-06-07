import axios from '../axios/axios'
import someAx from '../axios/axios'

export default {
    namespaced: 'auth',
    state: {
        isAuth: false,
        currentUser: {}
    },
    mutations: {
        setUser(state, user) {
            state.currentUser = user
            localStorage.currentUser = JSON.stringify(user)

            if (!!user.email) {
                state.isAuth = true
                localStorage.isAuth = true
            } else {
                state.isAuth = false
                localStorage.isAuth = false
            }
        },
        logout(state) {
            state.isAuth = false;
            state.currentUser = {};
            localStorage.currentUser = JSON.stringify({})
            localStorage.isAuth = false
        }
    },
    getters: {
        getAuth: state => state.isAuth,
        getCurrentUser: state => state.currentUser
    },
    actions: {
        async signup({commit}, data) {
            try {
                const user = await axios.post('/signup', data)

                if (user.data !== []) {
                    console.log('Signup, user:', user.data);
                    commit('setUser', user.data);
                }
            } catch (e) {
                console.log(e);
            }
        },
        async login({commit}, data) {
            try {
                const user = await axios.post('/login', data)

                if (user.data !== []) {
                    console.log('Login, user:', user);
                    commit('setUser', user.data);
                }
            } catch (e) {
                console.log(e);
            }
        },
        async loadCurrentUser({commit}) {
            if (!!localStorage.currentUser && JSON.parse(localStorage.currentUser).email) {
                const user = JSON.parse(localStorage.currentUser)
                commit('setUser', user)
            }
        },
        async logout({commit}) {
            try {
                commit('logout');
                // return await axios.get('/logout')
            } catch (e) {
                console.log(e);
            }
        },
        async check() {
            try {
                // return await axios.get('/check')
            } catch (e) {
                console.log(e);
            }
        }
    }
}