import Vuex         from 'vuex'
import axios        from 'axios'

import {
  getField,
  updateField }     from 'vuex-map-fields'

export const defaultAuthState = () => ({
  isAuthenticated:  false,
  authUser: {
    id:             null,
    username:       null,
    email:          null,
    first_name:     null,
    last_name:      null,
    groups:         [],
    service_set:    []
  },
  authLevel:        {
    admin:          false,
    regular:        false,
    support:        false,
  },
})

const state = () => defaultAuthState();

export const mutations = {
  updateField,
  RESET_AUTH_STATE(state) {
    Object.assign(state, defaultAuthState())
  },
  SET_AUTH_USER(state, payload) {
    state.authUser = payload
  },
  SET_IS_AUTHENTICATED(state, payload) {
    state.isAuthenticated = payload
  },
  SET_AUTH_LEVEL(state, payload) {
    state.authLevel = payload
  },
  updateToken(state, newToken) {
    state.auth = newToken
  },
  removeToken(state) {
    state.auth = null;
  }
}

export const actions = {
  resetAuthState({ commit }) {
    commit('RESET_AUTH_STATE')
  },
  authUser(context, payload) {
    context.commit('SET_AUTH_USER', payload)
  },
  authUserAuthenticated(context, payload) {
    context.commit('SET_IS_AUTHENTICATED', payload)
  },
  authLevel(context, payload) {
    context.commit('SET_AUTH_LEVEL', payload)
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