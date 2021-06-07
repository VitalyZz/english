import { createStore } from 'vuex'
import simulators from '@/store/simulators.module'
import auth from '@/store/auth.module'

export default createStore({
  state: {
  },
  mutations: {
  },
  actions: {
  },
  getters: {
  },
  modules: {
    simulators,
    auth
  }
})
