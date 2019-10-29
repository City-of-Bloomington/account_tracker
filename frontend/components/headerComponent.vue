<template>
  <div>
    <example-header
      :logo="{
        url:          `${cityWebsiteUrl }`,
        image:        `${headerLogo}`,
        imageAlt:     `${cityName}`
      }"

      :logoHeadings="{
        topHeading:   `${logoHeading}`,
        subHeading:   `${logoSubHeading}`,
      }"

      :application="{
        name:         `${appName}`,
        url:          frontendAppUrl
      }"

      :navItems="[
        {
          name: 'Employees',
          href: `${frontendAppUrl}employees/`
        },
        {
          name: 'Account Requests',
          href: `${frontendAppUrl}account_requests/`
        },
        {
          name: 'Resource Requests',
          href: `${frontendAppUrl}resource_requests/`
        },
        {
          name: 'Resources',
          href: `${frontendAppUrl}resources/`
        },
      ]"

      :subNavItems="null"
      >
      <fn1-dropdown
        slot="dropdown"
        :text="fullName"
        navAlign="right"
        :navItems="[

          {
            name: 'My Profile',
            href: `${frontendAppUrl}profile/`
          },
          {
            name: 'Logout',
            href: logoutRoute
          }
        ]"/>
    </example-header>
  </div>
</template>

<script>
import exampleHeader  from '~/components/design-system/exampleHeader'
import {
    mapFields }       from 'vuex-map-fields'

export default {
  components: {exampleHeader},
  data() {
    return {


    }
  },
  computed: {
    ...mapFields([
      'auth.authUser'
    ]),
    fullName() {
      return `${this.authUser.firstname} ${this.authUser.lastname}`
    }
  }
}
</script>

<style lang="scss" scoped>
  header {
    padding: 15px 20px !important;
    border-bottom: 1px solid $color-grey;

    ::v-deep a {
      &:first-of-type {
        img {
          @media (max-width: 575px) {
            width: 35px !important;
            height: 35px !important;
            margin: 0 !important;
          }
        }
      }
    }


  }

  @media (max-width: 575px) {
    header {
      ::v-deep div {

        a > :not(nav) {
        }
      }

      ::v-deep a {

        h1, h2 {
          display: none;
        }
      }
    }
  }

  @media (min-width: 576px) and (max-width: 767px) {
    header {
      ::v-deep a {
        &:first-of-type {

          img {
            width: 35px !important;
            height: 35px !important;
            margin: 0 !important;
          }
        }

        h1, h2 {
          display: none;
        }
      }
    }
  }

  @media (min-width: 768px) and (max-width: 991px) {

  }
</style>