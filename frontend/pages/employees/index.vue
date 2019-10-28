<template>
  <div>
    <pageTitleHeader
      page-title="Employees" />
      
    <form
      id="employee-search"
      class="inline">
      <fn1-input
        v-model="searchFields.firstname"
        label="First Name"
        placeholder="First Name"
        name="firstname"
        id="firstname" />

      <fn1-input
        v-model="searchFields.lastname"
        label="Last Name"
        placeholder="Last Name"
        name="lastname"
        id="lastname" />

      <button
        @click.prevent="searchEmployees(searchFields)"
        type="submit">Search</button>
    </form>

    <fn1-alert
      v-if="employees.error"
      variant="warning">
      <p><strong>{{ employees.error }}</strong></p>
    </fn1-alert>

    <table v-if="employees.response">
      <caption class="sr-only">
        New World Users Table
      </caption>

      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Username</th>
          <th scope="col">Department</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="p, i in employees.response">
          <th scope="row">{{ p.firstname }} {{ p.lastname }}</th>
          <td>{{ p.username }}</td>
          <td>{{ p.department }}</td>
          <td>
            <nuxt-link
              class="button"
              :to="`/employees/${p.number}`">
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
  import axios        from 'axios'

  import pageTitleHeader  from '~/components/pageTitleHeader'

  export default {
    components: { pageTitleHeader },
    beforeRouteEnter(to, from, next) {
      
      let firstNameParam = to.query.firstname,
           lastNameParam = to.query.lastname;

      if(
        firstNameParam !== undefined ||
        lastNameParam  !== undefined
        ){
        next(vm => {
          vm.searchEmployees(to.query);
        })
      }
      next();
    },
    data () {
      return  {
        searchFields: {
          firstname:  this.$route.query.firstname || '',
          lastname:   this.$route.query.lastname  || '',
        },
        employees: {
          response: null,
          error:    null,
        }
      }
    },
    created() {},
    mounted() {},
    computed: {},
    methods: {
      getSearchEmployees(params) {
        let regExTest        = /[a-zA-Z]{2,}/,
            fieldsTest       = regExTest.test(params.firstname || params.lastname),
            backendEmployees = `${process.env.backendUrl}${process.env.backendEmployees}firstname=${params.firstname}&lastname=${params.lastname}`;

        return new Promise((resolve, reject) => {
          if(fieldsTest) {
            axios.get(backendEmployees, { withCredentials: true })
            .then((res) => {
              if(res.data.length) {
                resolve(res.data)
              } else {
                reject('Sorry no results.')
              }
            })
          .catch((e)  => { reject(e)});
          } else {
            reject('Note: Search field should be at least 2 characters.')
          }
        })
      },
      searchEmployees(params) {
        this.getSearchEmployees(params)
        .then((res) => {
          // let queryParams = params;

          // for(var field in queryParams)
          //   if(!queryParams[field])
          //     delete queryParams[field];

          // this.$router.push({ query: queryParams });
          this.$router.push({ query: params});

          this.employees.response = res;
          this.employees.error = null;
        })
        .catch((e) => {
          this.employees.response = null;
          this.employees.error = e;
        })
      }
    }
  }
</script>

<style lang="scss">
form {
  &#employee-search {
    margin: 0 0 20px 0;
    padding: 0 0 20px 0;
    border-bottom: 1px solid lighten($text-color, 50%);
  }
}

table {
  thead {
    tr {
      th {
        &:last-of-type {
          text-align: right;
          padding-right: 8px;
        }
      }
    }
  }

  tbody {
    tr {
      th {
        text-align: left;
      }

      td {
        &:last-of-type {
          text-align: right;
        }
      }
    }
  }
}
</style>
