<template>
  <div>
    <exampleBreadCrumbs />

    <pageTitleHeader page-title="Your Profile" />

    <ul v-if="authUser">
      <li v-for="f, i in authUser.user">
        <strong>{{ i }}:</strong> {{ f }}
      </li>
    </ul>
  </div>
</template>

<script>
  import { mapFields }      from 'vuex-map-fields'

  import pageTitleHeader    from '~/components/pageTitleHeader'
  import exampleBreadCrumbs from '~/components/design-system/exampleBreadCrumbs'

  export default {
    components: { pageTitleHeader, exampleBreadCrumbs },
    created() {},
    data () {
      return  {}
    },
    computed: {
      ...mapFields([
        'auth.authUser',
      ])
    }
  }
</script>

<style>
</style>