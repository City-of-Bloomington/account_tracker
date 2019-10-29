<template>
  <div>
    <h1>Account Requests Page</h1>
    <ul>
      <li><strong>/account_requests</strong><br>
        -- Search for account_requests and list them
      </li>

      <li><strong>/account_requests/{id}</strong><br>
        -- View a pending account request
      </li>
    </ul>
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
      ...mapFields([])
    },
    methods: {}
  }
</script>

<style>
li {
  margin: 0 0 10px 0;
}
</style>
