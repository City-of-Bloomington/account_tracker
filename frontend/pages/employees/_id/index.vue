<template>
  <div>
    <pageTitleHeader
      :page-title="`Employee - ${employeeName}`">

      <fn1-button
        slot="buttons"
        class="activate button"
        @click.native="activateAccountRequest()">
        Initiate an Account Request
      </fn1-button>
    </pageTitleHeader>

    <main class="page-wrapper">
      <div class="page-description">
        <p>Viewing <strong>{{ employeeName }}'s</strong> Resource accounts.</p>
      </div>

      <form
        v-if="false"
        id="employee-resource-search"
        class="inline">
        <fn1-input
          autofocus
          v-model="searchFields.resourceName"
          label="Filter by Resource Name or field"
          placeholder="Resource Name or field"
          name="resource-name"
          id="resource-name" />
        
        <fn1-button
          class="cancel"
          @click.prevent.native="clearFilterEmployeeResources()">
          clear filter
        </fn1-button>
      </form>
      
      <fn1-tabs class="vertical-left" v-if="employee._embedded">
        <fn1-tab
          v-for="r, i in filteredServices"
          :key="r.definition.code"
          :name="r.definition.name" 
          :selected="selectedResource(r, i)">
          <div>
            <h2>{{ r.definition.name }}</h2>

            <table>
              <caption class="sr-only">
                {{ r.definition.name }} Table
              </caption>

              <col width="250">

              <thead>
                <tr>
                  <th scope="col">Field</th>
                  <th scope="col">Value</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="v, i in r.values">
                  <th scope="row">
                    <strong>{{ i }}</strong>
                  </th>
                  <td>
                    <template v-if="v">
                      {{ v }}
                    </template>
                    <template v-else>
                      - - -
                    </template>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </fn1-tab>
      </fn1-tabs>
    </main>
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'
  import axios        from 'axios'

  import pageTitleHeader  from '~/components/pageTitleHeader'

  export default {
    components: { pageTitleHeader },
    validate ({ params }) {
      return /^\d+$/.test(params.id)
    },
    created() {
      let backendEmployee = `${process.env.backendUrl}employees/${this.$route.params.id}?format=hal`;

      this.$axios.get(backendEmployee)
      .then((res) => {
        this.$store.dispatch('employee/setEmployee', res.data);
      })
      .catch((e)  => {
        this.employeeData.error = e;
        this.$store.dispatch('employee/resetEmployeeState');
      })
    },
    data() {
      return  {
        employeeData: {
          response: '',
          error:    '',
        },
        searchFields: {
          resourceName: '',
        }
      }
    },
    computed: {
      ...mapFields(['employee.employee']),
      employeeName(){
        if(this.employee) {
          if(this.employee.firstname || this.employee.lastname){
            return `${this.employee.firstname} ${this.employee.lastname}`
          } else if(this.employee.firstname) {
            return `${this.employee.firstname}`
          } else if(this.employee.lastname) {
            return `${this.employee.lastname}`
          } else {
            return 'No name set ...'
          }
        } else {
          return 'No employee {} set'
        }
      },
      filteredServices() {
        if(this.employee._embedded.resources.length) {
          return this.employee._embedded.resources
          .filter((r) => {
            let rName   = r.definition.name.toLowerCase();
            return rName.includes(this.searchFields.resourceName.toLowerCase())
          })
          .sort((a, b) => {
            let nameA = a.definition.name.toUpperCase(),
            nameB     = b.definition.name.toUpperCase();
            return (nameA < nameB) ? -1 : (nameA > nameB) ? 1 : 0;
          });
        }
      },
    },
    methods: {
      activateAccountRequest() {
        this.$router.push({
          path: `${this.$route.path}/activate`
        })
      },
      changeAccountRequest() {
        alert(`change`)
      },
      terminateAccountRequest() {
        alert(`terminate`)
      },
      clearFilterEmployeeResources() {
        this.searchFields.resourceName = '';
      },
      selectedResource(resource, index) {
        if(this.searchFields.resourceName == '') {
          return index == 0;
        } else {
          // console.dir('else this')
          // console.dir(this.filteredServices[0])
          // return this.filteredServices[0];
          return index == index;
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
form {
  &#employee-resource-search {
    margin: 0 0 20px 0;
    padding: 0 0 20px 0;
    border-bottom: 1px solid $color-grey-dark;
  }
}

table {
  thead,
  tbody {
    th, tr, td {

      &:first-child {
        text-align: right;
      }

      &:last-child {
        text-align: left;
      }
    }
  }
}
</style>
