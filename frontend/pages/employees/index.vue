<template>
  <div>
    <exampleBreadCrumbs />

    <pageTitleHeader page-title="Employees">
    
      <form
        slot="buttons"
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
        
        <fn1-button-group>
          <fn1-button
            class="search icon"
            @click.prevent.native="searchEmployees(searchFields)">
            search
          </fn1-button>

          <fn1-button
            class="cancel icon"
            @click.prevent.native="clearSearchEmployees()">
            clear
          </fn1-button>
        </fn1-button-group>
      </form>
    </pageTitleHeader>

    <main class="page-wrapper">

      <fn1-alert
        v-if="employees.error"
        variant="warning">
        <template v-if="employees.error">
          <p><strong>{{ employees.error }}</strong></p>
        </template>
      </fn1-alert>

      <template v-if="employeesData.length">
        <p>Showing <strong>{{ resultsCount }}</strong> results for:
        
        <strong>
          <template v-if="searchFields.firstname && searchFields.lastname">
            {{ searchFields.firstname }} {{ searchFields.lastname }}
          </template>
          
          <template v-else-if="searchFields.firstname">
            {{ searchFields.firstname }}
          </template>

          <template v-else-if="searchFields.lastname">
            {{ searchFields.lastname }}
          </template>
        </strong>
        </p>
      
        <table class="fixed-header">
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
            <tr v-for="p, i in employeesData">
              <th scope="row">
                <strong>{{ p.firstname }} {{ p.lastname }}</strong>
              </th>
              <td>
                <template v-if="p.username">
                  {{ p.username }}
                </template>
                <template v-else>
                  - - -
                </template>
              </td>
              <td>
                <template v-if="p.department">
                  {{ p.department }}
                </template>
                <template v-else>
                  - - -
                </template>
              </td>
              <td>
                <template v-for="l, i in p._links">
                  <template v-if="i == 'self'">
                    <nuxt-link
                      class="button"
                      :to="`/employees/${p.number}`">
                      View
                    </nuxt-link>
                  </template>
                </template>
              </td>
            </tr>
          </tbody>
        </table>
      </template>

      <template v-else>
        <h3>Search for an Employee</h3>
        <h4><strong>Note:</strong> <strong>Employees</strong> must exist in <strong>New World</strong> before appearing here.</h4>
      </template>
    </main>
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'
  import axios        from 'axios'

  import pageTitleHeader    from '~/components/pageTitleHeader'
  import exampleBreadCrumbs from '~/components/design-system/exampleBreadCrumbs'

  export default {
    components: { pageTitleHeader, exampleBreadCrumbs },
    beforeRouteEnter(to, from, next) {
      
      let firstNameParam = to.query.firstname,
           lastNameParam = to.query.lastname;

      if(
        firstNameParam !== undefined ||
        lastNameParam  !== undefined
        ){
        next(vm => {
          console.dir(to.query)
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
          data:     null,
          error:    null,
        }
      }
    },
    created() {},
    mounted() {},
    computed: {
      resultsCount() {
        if(this.employeesData)
          return this.employeesData.length
      },
      employeesData() {
        if(this.employees.data) {
          return this.employees.data._embedded.employees
        } else {
          return false;
        }
      },
    },
    methods: {
      getSearchEmployees(params) {
        let employeeParams,
            regExTest        = /[a-zA-Z]{2,}/,
            fieldsTest       = regExTest.test(params.firstname || params.lastname);

        if(params.firstname && params.lastname) {
          employeeParams = `firstname=${params.firstname}&lastname=${params.lastname}`;
        } else if(params.firstname) {
          employeeParams = `firstname=${params.firstname}`;
        } else if(params.lastname) {
          employeeParams = `lastname=${params.lastname}`;
        }

        let backendEmployees = `${process.env.backendUrl}${process.env.backendEmployees}${employeeParams}`;

        console.dir(backendEmployees)

        return new Promise((resolve, reject) => {
          if(fieldsTest) {
            axios.get(backendEmployees, { withCredentials: true })
            .then((res) => {
              if(res.data) {
                resolve(res.data)
              } else {
                reject('Sorry no results.')
              }
            })
            .catch((e)  => { reject(e)});
          } else {
            reject('Note: Fields should be at least 2 characters.')
          }
        })
      },
      searchEmployees(params) {
        let values = [];

        for (let value of Object.values(params)) {
          if(value !== '')
            values.push(value)
        }

        if(values.length) {
          let queryParams = params;
          for(var field in queryParams)
            if(!queryParams[field])
              delete queryParams[field];

          this.getSearchEmployees(queryParams)
          .then((res) => {
            this.$router.push({ query: queryParams});
            this.employees.data = res;
            this.employees.error = null;
          })
          .catch((e) => {
            this.employees.data = null;
            this.employees.error = e;
          })
        } else {
          this.employees.error = 'Please provide search values.'
        }
      },
      clearSearchEmployees() {
        this.resetEmployeeSearchData();
      },
      resetEmployeeSearchData() {
        this.$router.push({ query: null});

        // Search Fields
        this.searchFields.firstname = '';
        this.searchFields.lastname  = '';

        // Search Datas
        this.employees.data     = null;
        this.employees.error        = null;
      }
    }
  }
</script>

<style lang="scss">
h3 {
  font-weight: $weight-semi-bold;
}

h3, h4 {
  color: $text-color;
  margin: 0 0 20px 0;
}


form {
  &#employee-search {
    // margin: 0 0 20px 0;
    // padding: 0 0 20px 0;
    // border-bottom: 1px solid $color-grey-dark;
  }
}

table {
  margin: 20px 0 0 0;
  
    &.fixed-header {
      tbody {
        height: calc(100vh - 355px);
      }
    }
  }
</style>
