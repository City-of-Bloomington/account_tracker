import Vue          from 'vue'
import {
  mapFields }       from 'vuex-map-fields'

Vue.mixin({
  data() {
    return {
      cityWebsiteUrl:     process.env.cityWebsiteUrl,
      cityName:           process.env.cityName,
      frontendAppUrl:     `${process.env.frontendUrl}${process.env.frontendBase}`,
      logoHeading:        process.env.logoHeading,
      logoSubHeading:     process.env.logoSubHeading,
      headerLogo:         'city-of-bloomington-logo.svg',
      appName:            process.env.appName,
      logoutRoute:        `${process.env.backendUrl}logout`,

    }
  }
});