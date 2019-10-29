<template>
  <div>
    <h1>Resource Requests Page</h1>
    <ul>
      <li><strong>/resource_requests</strong><br>
        -- Search for and list [resource_requests]
      </li>

      <li><strong>/resource_requests/{id}</strong><br>
        -- Display a single submitted resource request
      </li>

      <li><strong>/resource_requests/{id}/approve</strong><br>
        -- Approve a single submitted resource request
      </li>

      <li><strong>/resource_requests/{id}/deny</strong><br>
        -- Deny a single submitted resource request
      </li>

      <li><strong>/resource_requests/{id}/update</strong><br>
        -- Update a single submitted resource request
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
