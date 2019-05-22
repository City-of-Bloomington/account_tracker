const pkg = require('./package')
require('dotenv').config()

module.exports = {
  mode: 'universal',

  // buildDir: 'nuxt-dist',

  dev:  (process.env.NODE_ENV !== 'production'),

  router: {
    base: '/frontend/'
  },

  // build: {
  //   publicPath: '/frontend/'
  // },

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
    nuxtPort:       process.env.NUXT_PORT || 9090,

    api:            process.env.AT_API || `https://dhcp-cityhall-101-164.bloomington.in.gov:9090/`,
    login:          process.env.AT_LOGIN               || `accounts/login/`,
    logout:         process.env.AT_LOGOUT              || `accounts/logout/`,
    user:           process.env.AT_API_USER            || `api/user/`,
    employee:       process.env.AT_API_EMPLOYEE        || `api/employee/`,
    action:         process.env.AT_API_ACTION          || `api/action/`,
    profile:        process.env.AT_API_PROFILE         || `api/profile/`,
    service:        process.env.AT_API_SERVICE         || `api/service/`,
    serviceReq:     process.env.AT_API_SERVICE_REQ     || `api/service-request/`,
    serviceManager: process.env.AT_API_SERVICE_MANAGER || `api/service-manager/`,
    accountRequest: process.env.AT_API_ACCOUNT_REQUEST || `api/account-request/`,

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
    ['@nuxtjs/redirect-module'],
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

  /**
   * note: these axios/proxy settings only apply
   * for SSR dev
   *
   */
  redirect: [
    { from: '^/accounts/login', to: '/accounts/login/' }
    // {
    //   from: '^(.*)$',
    //   to: (from, req) => {
    //     let trailingUrl = req.url.endsWith('/') ? req.url : req.url + '/'
    //     return trailingUrl
    //   }
    // }
  ],
  axios: {
    proxy: true,
  },
  proxy: {
    '/api/': {
      target: 'http://127.0.0.1:8000/api/',
      pathRewrite: {
        '^/api/': ''
      }
    },
    '/accounts/login/': {
      target: 'http://127.0.0.1:8000/accounts/login/',
      pathRewrite: {
        '^/accounts/login/': ''
      }
    },
    '/accounts/logout/': {
      target: 'http://127.0.0.1:8000/accounts/logout/',
      pathRewrite: {
        '^/accounts/logout/': ''
      }
    },
  },

  build: {
    extend(config, ctx) {
      if(!ctx.isDev) {
        config.output.publicPath = '/frontend/_nuxt/'
      }
    }
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
