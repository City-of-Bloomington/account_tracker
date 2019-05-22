import {
  getField,
  updateField }     from 'vuex-map-fields';

export const defaultNavState = () => ({
  application: {
    name: 'Account Track',
    url: `${process.env.FE_BASE}`,
  },
  nav: {
    admin: [
      { name: 'Account Requests',
        href: `${process.env.FE_BASE}account-requests`
      },
      { name: 'Service Requests',
        href: `${process.env.FE_BASE}service-requests`
      },
      { name: 'Services',
        href: `${process.env.FE_BASE}services`
      },
      { name: 'Profiles',
        href: `${process.env.FE_BASE}profiles`
      },
    ],
    regular: [
      { name: 'Account Requests',
        href: `${process.env.FE_BASE}account-requests`
      }
    ],
    support: [
      { name: 'Account Requests',
        href: `${process.env.FE_BASE}account-requests`
      },
      { name: 'Service Requests',
        href: `${process.env.FE_BASE}service-requests`
      },
      { name: 'Services',
        href: `${process.env.FE_BASE}services`
      },
    ],
  },
  subNav: {
    accountRequest: {
      admin: [
        { name: 'Create Account Request',
          href: `${process.env.FE_BASE}create`
        },
        { name: 'Created',
          href: `${process.env.FE_BASE}account-requests/created`
        },
        { name: 'Denied',
          href: `${process.env.FE_BASE}account-requests/denied`
        },
      ],
      regular: [
        { name: 'Create Account Request',
          href: `${process.env.FE_BASE}create`
        }
      ],
      support: [
        { name: 'Create Account Request',
          href: `${process.env.FE_BASE}create`
        }
      ],
    }
  }
})

const state = () => defaultNavState();

const mutations = {
  updateField,
}

const actions = {
}

const getters = {
  getField
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
};