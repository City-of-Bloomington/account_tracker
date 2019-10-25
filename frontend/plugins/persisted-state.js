import createPersistedState from 'vuex-persistedstate';
// import Cookies from 'js-cookie'


export default ({store, isHMR}) => {
  if (isHMR) return;

  if (process.client) {
    window.onNuxtReady((nuxt) => {
      createPersistedState({
        // reducer (state, paths) {
        //   const reducer = Object.assign({}, state);
        //     delete reducer.auth;
        //   return reducer;
        // }
      })(store)
    });
  }
}