import Vuex         from 'vuex'
import axios        from 'axios'

import {
  getField,
  updateField }     from 'vuex-map-fields'

export const defaultEmployeeIDState = () => ({
  employee:  {},
})

const state = () => defaultEmployeeIDState();

export const mutations = {
  updateField,
  RESET_EMPLOYEE(state) {
    Object.assign(state, defaultEmployeeIDState())
  },

  EMPLOYEE(state, payload) {
    state.employee = payload
  },
}

export const actions = {
  resetEmployeeState({ commit }) {
    commit('RESET_EMPLOYEE')
  },

  setEmployee(context, payload) {
    context.commit('EMPLOYEE', payload)
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