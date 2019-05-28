<template>
  <div class="header-wrapper" ref="headerWrapper">
    <exampleHeader
      :active.sync="$route.name"
      :logo="{
        url:          'https://bloomington.in.gov/',
        image:        `./city-of-bloomington-logo.svg`,
        imageAlt:     'City of Bloomington, IN'
      }"

      :logoHeadings="{
        topHeading:   'Bloomington.in.gov',
        subHeading:   'John Hamilton, Mayor',
      }"

      :application="navigation.application"
      :navItems="navItems()"
      :subNavItems="subNavItems()">

      <fn1-button slot="dropdown"
                  :title="`Logout`"
                  @click.native="logout()">
        Logout
      </fn1-button>
    </exampleHeader>
  </div>
</template>

<script>
import {
  mapState,
  mapMutations,
  mapGetters,
  mapActions }         from 'vuex'
import {
  mapFields }          from 'vuex-map-fields'

import exampleDropdown from '~/components/exampleDropdown'
import exampleHeader   from '~/components/exampleHeader'

export default {
  mounted(context) {
    console.log(`%c Made with ❤️ via City of Bloomington, ITS `,
      this.consoleLog.info)

    this.routeParam = this.$route.name;

    this.$nextTick()
    .then(() =>
      this.checkAuthLevel
      .then((resolve) => {
        this.navItems();
        this.subNavItems();
      })
      .catch((reject) => {
        console.log(`%c checkAuthLevel 🛑 `,
                    this.consoleLog.error,
                    `\n\n ${reject} \n\n`);
      })
    );
  },
  components: {
    exampleDropdown,
    exampleHeader
  },
  data() {
    return {
      routeParam:   '',
      routes: {
        accountReq: ['index','account-requests','account-requests-id','create'],
        serviceReq: ['service-requests'],
        services:   ['services'],
        profiles:   ['profiles'],
      }
    }
  },
  computed: {
    ...mapFields([
      'consoleLog',
      'auth.authUser',
      'auth.authLevel',
      'auth.isAuthenticated',
      'navigation',
    ]),

    isAccountRequestRoute() {
      if(this.routes.accountReq.includes(this.routeParam))
        return true;
    },
    isServiceRequestRoute() {
      if(this.routes.serviceReq.includes(this.routeParam))
        return true;
    },
    isServicesRoute() {
      if(this.routes.services.includes(this.routeParam))
        return true;
    },
    isProfilesRoute() {
      if(this.routes.profiles.includes(this.routeParam))
        return true;
    },
  },
  methods: {
    navItems() {
      if(this.authLevel.admin) {
        return this.navigation.nav.admin
      } else if (this.authLevel.regular) {
        return this.navigation.nav.regular
      } else if (this.authLevel.support) {
        return this.navigation.nav.support
      }
    },
    subNavItems() {
      if(this.isAccountRequestRoute) {
        if(this.authLevel.admin) {
          return this.navigation.subNav.accountRequest.admin;
        } else if(this.authLevel.regular) {
          return this.navigation.subNav.accountRequest.regular;
        } else if(this.authLevel.support) {
          return this.navigation.subNav.accountRequest.support;
        }
      } else {
        return [{}]
      }
    },
    logout() {
      this.resetGlobalStore();
      this.clearAuth();
      this.clearStorage();
      window.location = process.env.api + process.env.logout
    },
  },
}
</script>

<style lang="scss" scoped>
header {
  padding: 15px 20px;
  position: fixed;
  top: 0;
  z-index: 0 !important;

  /deep/ nav {
    &[role="navigation"] {
      ul {
        li {
          a {
            &.active,
            &:hover {
              color: $color-blue;
            }
          }
        }
      }
    }
  }

  /deep/ button {
    font-size: $size-s;
    line-height: $size-s;
    padding: 10px 15px;
    margin: 0 0 0 15px;
    background-color: $text-color;
    height: fit-content;
    display: flex;
    align-items: center;
    align-self: center;
  }
}
</style>