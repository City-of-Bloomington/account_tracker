<template>
  <div>
    <h1>Your Profile</h1>
    <pre>{{ authUser }}</pre>
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'

  export default {
    created() {
    },
    data () {
      return  {
      }
    },
    computed: {
      ...mapFields([
        'auth.authUser',
      ])
    },
    methods: {}
  }
</script>

<style>
</style>
