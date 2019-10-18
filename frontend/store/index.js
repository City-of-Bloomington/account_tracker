import Vuex         from 'vuex'
import axios        from 'axios'

import {
  getField,
  updateField }     from 'vuex-map-fields'

import auth         from './modules/auth'

export const strict = false;

export const defaultState = () => ({
  exampleNewWorldUsers: [
    {
      id:         1,
      name:       "Adam Butcher",
      department: "ITS"
    },
    {
      id:         2,
      name:       "Cliff Ingham",
      department: "ITS"
    },
    {
      id:         3,
      name:       "Seth Tierney",
      department: "ITS"
    },
    {
      id:         4,
      name:       "Matt Swinney",
      department: "HAND"
    }
  ]
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
  },
  mutations,
  actions,
  getters,
  state,
};
// export default Store