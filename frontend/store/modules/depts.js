import {
  getField,
  updateField }     from 'vuex-map-fields';

export const defaultDeptState = () => ({
  departments: [],
})

const state = () => defaultDeptState();

const mutations = {
  updateField,
  RESET_STATE(state) {
    Object.assign(state, defaultDeptState())
  },
  SET_DEPARTMENTS(state, payload) {
    state.departments = payload
  },
}

const actions = {
  resetState({ commit }) {
    commit('RESET_STATE')
  },
  setDepartments(context, payload) {
    context.commit("SET_DEPARTMENTS", payload)
  }
}

const getters = {
  getField
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
};