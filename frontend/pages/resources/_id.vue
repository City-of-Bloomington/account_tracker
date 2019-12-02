<template>
  <div>
    <h1>Resources {id ({{ resourceRouteID }}) } Page</h1>
    <ul>
      <li><strong>/resources/{id}</strong><br>
        -- Display information about a configured resource
      </li>
    </ul>

    <template v-if="resource">
      <pre>
        {{ resource }}
      </pre>
    </template>

    <template v-if="errors.resourceRoute">

      {{ errors.resourceRoute.status }} - {{ errors.resourceRoute.statusText }}
    </template>
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
      this.resourceRouteID = this.$route.params.id

      this.$axios
      .get(`${process.env.api}resources/${this.resourceRouteID}?format=json`)
      .then((res) => {
        this.resource = res.data;
      })
      .catch((e)  => {
        this.errors.resourceRoute = e.response;
      })
    },
    data () {
      return  {
        resourceRouteID: null,
        resource: null,
        errors: {
          resourceRoute: null
        }
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
