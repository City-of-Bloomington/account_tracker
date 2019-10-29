import Vuex         from 'vuex'
import axios        from 'axios'

import {
  getField,
  updateField }     from 'vuex-map-fields'

import auth         from './modules/auth'
import employee     from './modules/employee/employee'
import resources    from './modules/resources/resources'
import profiles     from './modules/profiles/profiles'

export const strict = false;

export const defaultState = () => ({
})

const state = () => defaultState();

export const mutations = {
  updateField,
  RESET_BASE_STATE(state) {
    Object.assign(state, defaultState())
  }
}

export const actions = {
  resetBaseState({ commit }) {
    commit('RESET_BASE_STATE')
  },
  /**
   * Note: we can only use this via SSR
   */
  async nuxtServerInit({ commit, state }, { redirect, req }) {
  }
}

export const getters = {
  getField
}

export default {
  namespaced:       true,
  modules: {
    auth:           auth,
    employee:       employee,
    resources:      resources,
    profiles:       profiles,
  },
  mutations,
  actions,
  getters,
  state,
};
// export default Store