import Vue          from 'vue'
import {
  mapState,
  mapMutations,
  mapGetters,
  mapActions }      from 'vuex'
import {
  mapFields }       from 'vuex-map-fields'

Vue.mixin({
  data() {
    return {
      levels: {
        admin: {
          admin:    true,
          regular:  false,
          support:  false,
        },
        regular: {
          admin:    false,
          regular:  true,
          support:  false,
        },
        support: {
          admin:    false,
          regular:  false,
          support:  true,
        },
      },
    }
  },
  computed: {
    ...mapFields([
      'groupLevels',
      'auth.authUser',
      'auth.authLevel',
    ]),
    checkAuthLevel() {
      return new Promise((resolve, reject) => {
        let adminLevel  = this.groupLevels.admin,
        regularLevel    = this.groupLevels.regular,
        supportLevel    = this.groupLevels.support,
        userLevels      = this.authUser.groups,
        isAdminLevel    = userLevels.includes(adminLevel),
        isRegularLevel  = userLevels.includes(regularLevel),
        isSupportLevel  = userLevels.includes(supportLevel);


        if(isAdminLevel) {
          // alert('admin')
          resolve(this.$store.dispatch('auth/authLevel', this.levels.admin))
        } else if(isRegularLevel) {
          // alert('reg')
          resolve(this.$store.dispatch('auth/authLevel', this.levels.regular))
        } else if(isSupportLevel) {
          // alert('sup')
          resolve(this.$store.dispatch('auth/authLevel', this.levels.support))
        } else {
          reject('checkAuthLevel failed')
        }
      })
    }
  },
})