<template>
  <div>
    <h1>Employee {ID} Page</h1>

    <strong>/employees/{username}</strong><br>
    -- Display someone's current accounts in the city

    <nuxt-link
      to="/employees/1/activate">
      Activate User
    </nuxt-link>
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'

  export default {
    validate ({ params }) {
      // Route must be a number
      return /^\d+$/.test(params.id)
    },
    created() {
      this.employeeRouteID = this.$route.params.id
    },
    data () {
      return  {
        employeeRouteID: null,
      }
    },
    computed: {
      ...mapFields(['exampleNewWorldUsers'])
    },
    methods: {}
  }
</script>

<style>
li {
  margin: 0 0 10px 0;
}
</style>
