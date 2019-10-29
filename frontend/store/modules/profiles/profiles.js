import Vuex         from 'vuex'
import axios        from 'axios'

import {
  getField,
  updateField }     from 'vuex-map-fields'

export const defaultProfilesState = () => ({
  profiles:  {},
})

const state = () => defaultProfilesState();

export const mutations = {
  updateField,
  RESET_PROFILES(state) {
    Object.assign(state, defaultProfilesState())
  },

  PROFILES(state, payload) {
    state.profiles = payload
  }
}

export const actions = {
  resetProfilesState({ commit }) {
    commit('RESET_PROFILES')
  },

  setProfiles(context, payload) {
    context.commit('PROFILES', payload)
  }
}

export const getters = {
  getField
}

export default {
  namespaced:   true,
  mutations,
  actions,
  getters,
  state,
};