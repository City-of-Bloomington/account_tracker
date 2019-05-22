import Vue          from 'vue'

Vue.mixin({
  methods: {
    clearAuth() {
      this.$store.dispatch("auth/resetAuthState");
    }
  }
})