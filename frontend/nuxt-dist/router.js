import Vue from 'vue'
import Router from 'vue-router'
import { interopDefault } from './utils'

const _3a89319d = () => interopDefault(import('../pages/account-requests/index.vue' /* webpackChunkName: "pages/account-requests/index" */))
const _3a2026b5 = () => interopDefault(import('../pages/create/index.vue' /* webpackChunkName: "pages/create/index" */))
const _1f74dcba = () => interopDefault(import('../pages/login.vue' /* webpackChunkName: "pages/login" */))
const _aada1eba = () => interopDefault(import('../pages/profiles/index.vue' /* webpackChunkName: "pages/profiles/index" */))
const _775d4fd6 = () => interopDefault(import('../pages/service-requests/index.vue' /* webpackChunkName: "pages/service-requests/index" */))
const _810d57d2 = () => interopDefault(import('../pages/services/index.vue' /* webpackChunkName: "pages/services/index" */))
const _249f42c0 = () => interopDefault(import('../pages/create/finish.vue' /* webpackChunkName: "pages/create/finish" */))
const _70e472c2 = () => interopDefault(import('../pages/create/five.vue' /* webpackChunkName: "pages/create/five" */))
const _03f58313 = () => interopDefault(import('../pages/create/four.vue' /* webpackChunkName: "pages/create/four" */))
const _0d555d81 = () => interopDefault(import('../pages/create/three.vue' /* webpackChunkName: "pages/create/three" */))
const _5e37ec22 = () => interopDefault(import('../pages/create/two.vue' /* webpackChunkName: "pages/create/two" */))
const _71fe1176 = () => interopDefault(import('../pages/account-requests/_id.vue' /* webpackChunkName: "pages/account-requests/_id" */))
const _269dee8c = () => interopDefault(import('../pages/index.vue' /* webpackChunkName: "pages/index" */))

Vue.use(Router)

if (process.client) {
  if ('scrollRestoration' in window.history) {
    window.history.scrollRestoration = 'manual'

    // reset scrollRestoration to auto when leaving page, allowing page reload
    // and back-navigation from other pages to use the browser to restore the
    // scrolling position.
    window.addEventListener('beforeunload', () => {
      window.history.scrollRestoration = 'auto'
    })

    // Setting scrollRestoration to manual again when returning to this page.
    window.addEventListener('load', () => {
      window.history.scrollRestoration = 'manual'
    })
  }
}
const scrollBehavior = function (to, from, savedPosition) {
  // if the returned position is falsy or an empty object,
  // will retain current scroll position.
  let position = false

  // if no children detected and scrollToTop is not explicitly disabled
  if (
    to.matched.length < 2 &&
    to.matched.every(r => r.components.default.options.scrollToTop !== false)
  ) {
    // scroll to the top of the page
    position = { x: 0, y: 0 }
  } else if (to.matched.some(r => r.components.default.options.scrollToTop)) {
    // if one of the children has scrollToTop option set to true
    position = { x: 0, y: 0 }
  }

  // savedPosition is only available for popstate navigations (back button)
  if (savedPosition) {
    position = savedPosition
  }

  return new Promise((resolve) => {
    // wait for the out transition to complete (if necessary)
    window.$nuxt.$once('triggerScroll', () => {
      // coords will be used if no selector is provided,
      // or if the selector didn't match any element.
      if (to.hash) {
        let hash = to.hash
        // CSS.escape() is not supported with IE and Edge.
        if (typeof window.CSS !== 'undefined' && typeof window.CSS.escape !== 'undefined') {
          hash = '#' + window.CSS.escape(hash.substr(1))
        }
        try {
          if (document.querySelector(hash)) {
            // scroll to anchor by returning the selector
            position = { selector: hash }
          }
        } catch (e) {
          console.warn('Failed to save scroll position. Please add CSS.escape() polyfill (https://github.com/mathiasbynens/CSS.escape).')
        }
      }
      resolve(position)
    })
  })
}

export function createRouter() {
  return new Router({
    mode: 'history',
    base: decodeURI('/frontend/'),
    linkActiveClass: 'nuxt-link-active',
    linkExactActiveClass: 'nuxt-link-exact-active',
    scrollBehavior,

    routes: [{
      path: "/account-requests",
      component: _3a89319d,
      name: "account-requests"
    }, {
      path: "/create",
      component: _3a2026b5,
      name: "create"
    }, {
      path: "/login",
      component: _1f74dcba,
      name: "login"
    }, {
      path: "/profiles",
      component: _aada1eba,
      name: "profiles"
    }, {
      path: "/service-requests",
      component: _775d4fd6,
      name: "service-requests"
    }, {
      path: "/services",
      component: _810d57d2,
      name: "services"
    }, {
      path: "/create/finish",
      component: _249f42c0,
      name: "create-finish"
    }, {
      path: "/create/five",
      component: _70e472c2,
      name: "create-five"
    }, {
      path: "/create/four",
      component: _03f58313,
      name: "create-four"
    }, {
      path: "/create/three",
      component: _0d555d81,
      name: "create-three"
    }, {
      path: "/create/two",
      component: _5e37ec22,
      name: "create-two"
    }, {
      path: "/account-requests/:id?",
      component: _71fe1176,
      name: "account-requests-id"
    }, {
      path: "/",
      component: _269dee8c,
      name: "index"
    }],

    fallback: false
  })
}
