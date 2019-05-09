const pkg = require('./package')
require('dotenv').config()

module.exports = {
  mode: 'universal',
  dev:  (process.env.NODE_ENV !== 'production'),

  router: {
    base: '/'
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
    repo:           pkg.repository.url,
    nuxtPort:       process.env.NUXT_PORT           || 8080,

    api:            process.env.API || `http://127.0.0.1:8000/api/`,
    obtainJWT:      process.env.API_OBTAIN_JWT      || `auth/obtain_token/`,
    refreshJWT:     process.env.API_REFRESH_JWT     || `auth/refresh_token/`,
    user:           process.env.API_USER            || `user/`,
    employee:       process.env.API_EMPLOYEE        || `employee/`,
    action:         process.env.API_ACTION          || `action/`,
    profile:        process.env.API_PROFILE         || `profile/`,
    service:        process.env.API_SERVICE         || `service/`,
    serviceReq:     process.env.API_SERVICE_REQ     || `service-request/`,
    serviceManager: process.env.API_SERVICE_MANAGER || `service-manager/`,
    accountRequest: process.env.API_ACCOUNT_REQUEST || `account-request/`,

    ttApi:          process.env.TT_API || `https://tomcat2.bloomington.in.gov/timetrack/`,
    deptService:    process.env.TT_API_DEPT_SERVICE     || `DepartmentService`,
    groupService:   process.env.TT_API_GROUP_SERVICE    || `GroupService`,
    jobService:     process.env.TT_API_JOB_SERVICE      || `JobTitleService`,
    posService:     process.env.TT_API_POSITION_SERVICE || `PositionService`,
    managerService: process.env.TT_API_MANAGER_SERVICE  || `ManagerService`,
  },

  loading: { color: '#fff' },

  plugins: [
    { src: '~/plugins/design-system' },
    { src: '~/plugins/axios' },
    { src: '~/plugins/filters' },
    { src: '~/plugins/mixins' },
    { src: '~/plugins/authLevel' },
    { src: '~/plugins/resetForm' },
    { src: '~/plugins/resetGlobalStore' },
    { src: '~/plugins/clearAuth' },
    { src: '~/plugins/clearStorage' },
    { src: '~/plugins/api-methods' },
    { src: '~/plugins/ttApi-methods' },
    { src: '~/plugins/localStorage', ssr: false },
    { src: '~/plugins/croppie.js',   ssr: false }
  ],

  modules: [
    ['@nuxtjs/style-resources'],
    ['@nuxtjs/axios'],
    // PWA having issues w/ nodemon
    // ['@nuxtjs/pwa', {
    //   icon: true,
    //   sizes: [16, 120, 144, 152, 192, 384, 512],
    // }]
  ],

  css: [
    '@/assets/css/style.scss',
    '@/assets/css/cobDS-tokens.scss',
  ],

  styleResources: {
    scss: [
      './assets/css/style.scss',
      './assets/css/cobDS-tokens.scss',
    ]
  },

  axios: {
    withCredentials: true
  },

  build: {
    // ESLint needs config for Nuxt
    // extend (config, { isDev, isClient }) {
    //   if (isDev && isClient) {
    //     config.module.rules.push({
    //       enforce: 'pre',
    //       test: /\.(js|vue)$/,
    //       loader: 'eslint-loader',
    //       exclude: /(node_modules)/
    //     })
    //   }
    // }
  }
}
