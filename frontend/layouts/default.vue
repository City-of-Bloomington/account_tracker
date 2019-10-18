<template>
  <div>
    <div v-if="!isAuthenticated">
      <h1>Please Login</h1>
    </div>

    <div v-if="isAuthenticated">
      <headerComponent />
      <div class="nuxt-wrapper">
        <nuxt />
      </div>
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
          console.dir(res);
          this.$store.dispatch('auth/authUser', res);
          this.$store.dispatch('auth/authUserAuthenticated', true);
          this.isAuthenticated = true;
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
        return new Promise((resolve, reject) => {
          this.$axios
          .get(`${process.env.backendUrl}profile?format=json`)
          .then((res) => { resolve(res.data) })
          .catch((e)  => { reject(e) });
        })
      }
    }
  }
</script>

<style>
.nuxt-wrapper {
  padding: 20px;
}
</style>