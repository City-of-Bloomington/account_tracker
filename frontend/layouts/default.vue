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
        let loginRoute = `${process.env.backendUrl}account?format=json`;

        return new Promise((resolve, reject) => {
          this.$axios.get(loginRoute)
          .then((res) => { resolve(res.data) })
          .catch((e)  => { reject(e) });
        })
      }
    }
  }
</script>

<style lang="scss">
.nuxt-wrapper {
  padding: 20px;
}

h1 {
  &.page-title {
    color: $text-color;
    font-weight: $weight-semi-bold;
    border-bottom: 1px solid lighten($text-color, 50%);
    margin: 0 0 40px 0;
    padding: 0 0 20px 0;
  }
}
</style>