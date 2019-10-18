<template>
  <div>
    <h1>Staff Page</h1>
    <ul>
      <li><strong>/employees</strong><br>
        -- Search for staff and list them
      </li>

      <li><strong>/employees/{username}</strong><br>
        -- Display someone's current accounts in the city
      </li>

      <li><strong>/employees/{username}/change</strong><br>
        -- Create an account_request changing someone
      </li>

      <li><strong>/employees/{username}/terminate</strong><br>
        -- Create an account_request terminating someone
      </li>

      <li>
        <strong>/employees/{ID}/activate</strong><br>
        -- Create an account_request for a Employees [Activation]
      </li>
    </ul>

    <table v-if="exampleNewWorldUsers">
      <caption class="sr-only">
        Example New World Users Table
      </caption>

      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Name</th>
          <th scope="col">Department</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="u, i in exampleNewWorldUsers">
          <th scope="row">{{ u.id }}</th>
          <td>{{ u.name }}</td>
          <td>{{ u.department }}</td>
          <td>
            <nuxt-link
              :to="`/employees/${u.id}`">
              view
            </nuxt-link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'

  export default {
    created() {},
    data () {
      return  {

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
