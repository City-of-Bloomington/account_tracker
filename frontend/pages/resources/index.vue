<template>
  <div>
    <h1>Resources Page</h1>
    <ul>
      <li><strong>/resources</strong><br>
        -- List all the configured resources
      </li>

      <li><strong>/resources/{id}</strong><br>
        -- Display information about a configured resource
      </li>
    </ul>

    <table v-if="resources">
      <caption class="sr-only">
        Resources Table
      </caption>

      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Code</th>
          <th scope="col">Name</th>
          <th scope="col">Type</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="r, i in resources">
          <th scope="row">
            <nuxt-link
              :to="`/resources/${r.id}`">
              {{ r.id }}
            </nuxt-link>
          </th>
          <td>{{ r.code }}</td>
          <td>{{ r.name }}</td>
          <td>{{ r.type }}</td>
          <td>add/mod/rm</td>
        </tr>
      </tbody>
    </table>

    <ul >
      <li >
        {{ r }}
      </li>
    </ul>
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'

  export default {
    created() {
      this.$axios
      .get(`${process.env.backendUrl}resources?format=json`)
      .then((res) => {
        this.resources = res.data;
      })
      .catch((e)  => {
        console.dir(e);
      })
    },
    data () {
      return  {
        resources: null,
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
