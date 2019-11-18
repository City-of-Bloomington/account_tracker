<template>
  <div v-if="isAuthenticated">
    <headerComponent />
    <div class="nuxt-wrapper">
      <nuxt />
    </div>
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'

  import headerComponent  from '~/components/headerComponent'

  export default {
    components: { headerComponent },
    // middleware: 'authenticated',
    created() {
      if(!this.isAuthenticated) {
        this.getUserProfile()
        .then((res) => {
          this.$store.dispatch('auth/authUser', res);
          this.$store.dispatch('auth/authUserAuthenticated', true);
        })
        .catch((error) => {
          this.$store.dispatch('auth/authUser', null);
          this.$store.dispatch('auth/authUserAuthenticated', false);

          if(error.response.status == 403) {
            if(process.browser) {
              let redirectURL      = `${process.env.frontendUrl}${process.env.frontendBase}`,
                encodedRedirectURL = encodeURI(redirectURL);

              window.location = `${process.env.backendUrl}login?return_url=${encodedRedirectURL}`;
            }
          }
        })
      }
    },
    data () {
      return  {}
    },
    computed: {
      ...mapFields([
        'auth.authUser',
        'auth.isAuthenticated'
      ])
    },
    methods: {
      getUserProfile() {
        let loginRoute = `${process.env.backendUrl}account?format=json`;

        return new Promise((resolve, reject) => {
          this.$axios.get(loginRoute, { withCredentials: true })
          .then((res) => { resolve(res.data) })
          .catch((e)  => { reject(e) });
        })
      }
    }
  }
</script>

<style lang="scss">
.nuxt-wrapper {
  padding: 0 20px;

  main {
    &.page-wrapper {}
  }
}
</style>