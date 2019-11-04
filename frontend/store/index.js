import Vuex         from 'vuex'
import axios        from 'axios'

var Cookies = require('cookies')

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
  async nuxtServerInit({ commit }, { req, redirect }) {
    // let loginRoute = `${process.env.backendUrl}account?format=json`;
    // try {
    //   let res = await this.$axios.$get(loginRoute, { withCredentials: true })
    //   console.dir(res)
    // } catch(e) {
    //   console.dir(e);
    //   if (e.response.status == 403) {
    //     console.dir('403');
    //     // let redirectURL        = `${process.env.frontendUrl}${process.env.frontendBase}`,
    //     //     encodedRedirectURL = encodeURI(redirectURL);

    //     // redirect(`${process.env.backendUrl}login?return_url=${encodedRedirectURL}`);
    //   }
    // }
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