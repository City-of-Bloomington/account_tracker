const pkg = require('./package')
require('dotenv').config()
// require('dotenv').config({path: `${process.env.SITE_HOME}/env`})

module.exports = {
  mode: 'universal',

  dev:  (process.env.NODE_ENV !== 'production'),

  router: {
    base: process.env.FE_BASE || '/frontend/',
  },


  head: {
    title: pkg.prettyName,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: pkg.description }
    ]
  },

  env: {
    siteHome:         process.env.SITE_HOME,
    repo:             pkg.repository.url,
    nuxtPort:         process.env.NUXT_PORT             || 3000,
    appName:          process.env.APP_NAME,
    cityName:         process.env.CITY_NAME,
    logoHeading:      process.env.LOGO_HEADING,
    logoSubHeading:   process.env.LOGO_SUB_HEADING,
    cityWebsiteUrl:   process.env.CITY_WEBSITE_URL,

    frontendBase:     process.env.FE_BASE               || '/frontend/',
    frontendUrl:      process.env.FRONTEND_URL          || 'https://dhcp-cityhall-101-164.bloomington.in.gov:3000',

    api:              process.env.BACKEND_URL           || 'https://rogue.bloomington.in.gov/account_tracker/',
    apiUsersAccount:  process.env.BACKEND_USERS_ACCOUNT || 'account?format=json',
    apiAR:            process.env.BACKEND_AR            || 'account_requests?format=hal',
    apiPendingAR:     process.env.BACKEND_PENDING_AR    || 'account_requests?status=pending&format=hal',
    apiCompletedAR:   process.env.BACKEND_COMPLETED_AR  || 'account_requests?status=completed&format=hal',
    apiResources:     process.env.BACKEND_RESOURCES     || 'resources?format=hal',
    apiProfiles:      process.env.BACKEND_PROFILES      || 'profiles?format=json',
    apiEmployees:     process.env.BACKEND_EMPLOYEES     || 'employees?format=hal'
  },

  loading: { color: '#fff' },

  plugins: [
    // { src: '~/plugins/persisted-state', ssr: false },
    { src: '~/plugins/axios' },
    { src: '~/plugins/design-system' },
    { src: '~/plugins/universal-data'},
    { src: '~/plugins/timetrack/api-methods'}
  ],

  modules: [
    ['@nuxtjs/style-resources'],
    ['@nuxtjs/axios'],
    // @nuxtjs/pwa breaks
    // ['@nuxtjs/pwa', {
    //   icon: true,
    //   sizes: [16, 120, 144, 152, 192, 384, 512],
    // }]
  ],

  css: [
    '@/assets/scss/style.scss',
  ],

  styleResources: {
    scss: [
      './assets/scss/cobDS-tokens.scss',
    ]
  },

  /**
   * note: these axios/proxy settings only apply
   * for SSR dev
   *
   */
  axios: {
    proxy: true,
  },

  proxy: {
    // '/api/': {
    //   target: 'https://rogue.bloomington.in.gov/account_tracker/',
    //   pathRewrite: {
    //     '^/api/': ''
    //   }
    // },
  },

  buildModules: [
    '@nuxtjs/moment'
  ],

  build: {
    extend(config, ctx) {
      if(!ctx.isDev) {
        config.output.publicPath = '/account-tracker/_nuxt/'
      }
    }
  }
}
