import Vuex         from 'vuex'
import axios        from 'axios'

import {
  getField,
  updateField }     from 'vuex-map-fields'

export const defaultResourcesState = () => ({
  resources:  {},
  activeDirectoryDepartments: []
})

const state = () => defaultResourcesState();

export const mutations = {
  updateField,
  RESET_RESOURCES(state) {
    Object.assign(state, defaultResourcesState())
  },

  RESOURCES(state, payload) {
    state.resources = payload
  },

  RESOURCE_AD_DEPARTMENTS(state, payload) {
    state.activeDirectoryDepartments = payload
  },
}

export const actions = {
  resetResourcesState({ commit }) {
    commit('RESET_RESOURCES')
  },

  setResources(context, payload) {
    context.commit('RESOURCES', payload)
  },

  setResourceADDepartments(context, payload) {
    context.commit('RESOURCE_AD_DEPARTMENTS', payload)
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