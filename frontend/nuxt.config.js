const pkg = require('./package')


module.exports = {
  mode: 'universal',

  /*
  ** Headers of the page
  */
  head: {
    title: 'Account Track',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: pkg.description }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  env: {
    api:            'http://127.0.0.1:8000/api/',
    obtainJWT:      'auth/obtain_token/',
    refreshJWT:     'auth/refresh_token/',
    user:           'user/',
    action:         'action/',
    profile:        'profile/',
    service:        'service/',
    serviceReq:     'service-request/',
    accountRequest: 'account-request/',


    ttApi:          'https://tomcat2.bloomington.in.gov/timetrack/',
    deptService:    'DepartmentService',
    groupService:   'GroupService',
    jobService:     'JobTitleService',
    posService:     'PositionService',
    managerService: 'ManagerService',
  },

  /*
  ** Customize the progress-bar color
  */
  loading: { color: '#fff' },

  /*
  ** Global CSS
  */
  css: [
    './assets/style.scss',
    './assets/cobDS-tokens.scss',
  ],

  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    { src: '~/plugins/design-system' },
    { src: '~/plugins/axios' },
    { src: '~/plugins/filters' },
    { src: '~/plugins/localStorage', ssr: false },
    { src: '~/plugins/mixins' },
    { src: '~/plugins/authLevel' },
    { src: '~/plugins/resetForm' },
    { src: '~/plugins/resetGlobalStore' },
    { src: '~/plugins/clearAuth' },
    { src: '~/plugins/clearStorage' },
    { src: '~/plugins/croppie.js', ssr: false }
  ],

  /*
  ** Nuxt.js modules
  */
  modules: [
    { src: '@nuxtjs/axios' }
  ],

  // axios: {
  //   withCredentials: true
  // },

  /*
  ** Build configuration
  */
  build: {
    /*
    ** You can extend webpack config here
    */
    extend(config, ctx) {

    }
  }
}
