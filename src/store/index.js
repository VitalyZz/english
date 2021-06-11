import { createStore } from 'vuex'
import simulators from '@/store/simulators.module'
import auth from '@/store/auth.module'
import colors from '@/store/colors.module'

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
    auth,
    colors
  }
})
