<template>
  <div>
    <exampleBreadCrumbs />
    
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
        <p>Viewing <strong>{{ employeeName }}'s</strong> accounts.</p>
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
        <fn1-tab name="Employee" selected="true" class="employee">
          <svg v-if="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512">
            <path fill="#4f4f4f" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 96c48.6 0 88 39.4 88 88s-39.4 88-88 88-88-39.4-88-88 39.4-88 88-88zm0 344c-58.7 0-111.3-26.6-146.5-68.2 18.8-35.4 55.6-59.8 98.5-59.8 2.4 0 4.8.4 7.1 1.1 13 4.2 26.6 6.9 40.9 6.9 14.3 0 28-2.7 40.9-6.9 2.3-.7 4.7-1.1 7.1-1.1 42.9 0 79.7 24.4 98.5 59.8C359.3 421.4 306.7 448 248 448z" class=""></path>
          </svg>
          
          <div class="employee-data">
            <div v-if="employee.firstname || employee.lastname">
              <p><strong>Name:</strong>
              <template v-if="employee.firstname">
                {{ employee.firstname }}
              </template>

              <template v-if="employee.lastname">
                {{ employee.lastname }}
              </template></p>
            </div>

            <div v-if="employee.username">
              <p><strong>Username:</strong> {{ employee.username }}</p>
            </div>

            <div v-if="employee.number">
              <p><strong>Employee Number:</strong> {{ employee.number }}</p>
            </div>

            <div v-if="employee.department">
              <p><strong>Department:</strong> {{ employee.department }}</p>
            </div>
          </div>
        </fn1-tab>

        <fn1-tab name="Account Requests" class="account-requests">
          <fn1-tabs class="account-request-tabs">
            <fn1-tab
              :name="`Pending (${pendingAccountRequestCount})`"
              :selected="true"
              v-if="employeeData.accountRequests.pending.response">
              <table class="fixed-header account-requests">
                <caption class="sr-only">
                  {{ employeeName }}'s Account Requests Table
                </caption>

                <thead>
                  <tr>
                    <th scope="col">Created Date</th>
                    <th scope="col">Name</th>
                    <th scope="col">Requester</th>
                    <th scope="col">Type</th>
                    <th scope="col">Department</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>

                <tbody>
                  <template v-for="ar, i in employeeData.accountRequests.pending.response._embedded.account_requests">
                    <tr>
                      <th scope="row">
                        <template v-if="ar.created.date">
                          {{ $moment(ar.created.date).format('MM/DD/YYYY') }}<br>
                          <small>{{ $moment(ar.created.date).format(" h:mm:ss a") }}</small>
                        </template>

                        <template v-else>
                          - - -
                        </template>
                      </th>

                      <td>
                        <strong>{{ ar.employee.firstname }} {{ ar.employee.lastname }}</strong>
                      </td>

                      <td>
                        <template v-if="ar.requester_username">
                          {{ ar.requester_username }}
                        </template>

                        <template v-else>
                          - - -
                        </template>
                      </td>

                      <td>
                        <template v-if="ar.type">
                          {{ ar.type }}
                        </template>

                        <template v-else>
                          - - -
                        </template>
                      </td>

                      <td>
                        <template v-if="ar.employee.department">
                          {{ ar.employee.department }}
                        </template>
                        <template v-else>
                          - - -
                        </template>
                      </td>

                      <td>
                        <template v-for="l, i in ar._links">
                          <template v-if="i == 'self'">
                            <nuxt-link
                              class="button"
                              :to="`/account_requests/${ar.id}`">
                              View
                            </nuxt-link>
                          </template>
                        </template>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>

              <examplePagination
                v-if="pendingAccountRequestCount > 20"
                :total="pendingAccountRequestCount"
                :per-page="employeeData.accountRequests.pending.response.itemsPerPage"
                :current-page="currentPage"
                @pagechanged="onPendingPageChange" />
            </fn1-tab>

            <fn1-tab
              :name="`Completed (${completedAccountRequestCount})`"
              v-if="employeeData.accountRequests.completed.response">
              <table class="fixed-header account-requests">
                <caption class="sr-only">
                  {{ employeeName }}'s Account Requests Table
                </caption>

                <thead>
                  <tr>
                    <th scope="col">Created Date</th>
                    <th scope="col">Name</th>
                    <th scope="col">Requester</th>
                    <th scope="col">Type</th>
                    <th scope="col">Department</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>

                <tbody>
                  <template v-for="ar, i in employeeData.accountRequests.completed.response._embedded.account_requests">
                    <tr>
                      <th scope="row">
                        <template v-if="ar.created.date">
                          {{ $moment(ar.created.date).format('MM/DD/YYYY') }}<br>
                          <small>{{ $moment(ar.created.date).format(" h:mm:ss a") }}</small>
                        </template>

                        <template v-else>
                          - - -
                        </template>
                      </th>

                      <td>
                        <strong>{{ ar.employee.firstname }} {{ ar.employee.lastname }}</strong>
                      </td>

                      <td>
                        <template v-if="ar.requester_username">
                          {{ ar.requester_username }}
                        </template>

                        <template v-else>
                          - - -
                        </template>
                      </td>

                      <td>
                        <template v-if="ar.type">
                          {{ ar.type }}
                        </template>

                        <template v-else>
                          - - -
                        </template>
                      </td>

                      <td>
                        <template v-if="ar.employee.department">
                          {{ ar.employee.department }}
                        </template>
                        <template v-else>
                          - - -
                        </template>
                      </td>

                      <td>
                        <template v-for="l, i in ar._links">
                          <template v-if="i == 'self'">
                            <nuxt-link
                              class="button"
                              :to="`/account_requests/${ar.id}`">
                              View
                            </nuxt-link>
                          </template>
                        </template>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>

              <examplePagination
                v-if="completedAccountRequestCount > 20"
                :total="completedAccountRequestCount"
                :per-page="employeeData.accountRequests.completed.response.itemsPerPage"
                :current-page="currentPage"
                @pagechanged="onCompletedPageChange" />
            </fn1-tab>
          </fn1-tabs>
        </fn1-tab>

        <fn1-tab
          v-for="r, i in filteredServices"
          :key="r.definition.code"
          :name="r.definition.name">
          <div>
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
  import { mapFields }      from 'vuex-map-fields'
  import axios              from 'axios'

  import pageTitleHeader    from '~/components/pageTitleHeader'
  import exampleBreadCrumbs from '~/components/design-system/exampleBreadCrumbs'
  import examplePagination  from '~/components/design-system/examplePagination'

  export default {
    components: { pageTitleHeader, exampleBreadCrumbs, examplePagination },
    validate ({ params }) {
      return /^\d+$/.test(params.id)
    },
    created() {
      let backendEmployee = `${process.env.api}employees/${this.$route.params.id}?format=hal`;
      this.$axios.get(backendEmployee)
      .then((res) => {
        this.$store.dispatch('employee/setEmployee', res.data);
      })
      .catch((e)  => {
        this.employeeData.error = e;
        this.$store.dispatch('employee/resetEmployeeState');
      });

      this.getEmployeePendingAccountRequests()
      .then((res) => {
        this.employeeData.accountRequests.pending.response = res;
      })
      .catch((e)  => {
        this.employeeData.accountRequests.pending.error = e;
      });

       this.getEmployeeCompletedAccountRequests()
      .then((res) => {
        this.employeeData.accountRequests.completed.response = res;
      })
      .catch((e)  => {
        this.employeeData.accountRequests.completed.error = e;
      });
    },
    data() {
      return  {
        currentPage:      1,
        employeeData: {
          response:       '',
          error:          '',
          accountRequests: {
            pending: {
              response:   null,
              error:      null,
            },
            completed: {
              response:   null,
              error:      null,
            }
          },
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
      pendingAccountRequestCount() {
        if(this.employeeData.accountRequests.pending.response)
          return this.employeeData.accountRequests.pending.response.total
      },
      completedAccountRequestCount() {
        if(this.employeeData.accountRequests.completed.response)
          return this.employeeData.accountRequests.completed.response.total
      }
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
      },
      getEmployeePendingAccountRequests(page) {
        if(page) {
          var backendEmployeePendingAccountRequests = `${process.env.api}account_requests?employee_number=${this.$route.params.id}&status=pending&format=hal&page=${page}`;
        } else {
          var backendEmployeePendingAccountRequests = `${process.env.api}account_requests?employee_number=${this.$route.params.id}&status=pending&format=hal`;
        }

        return new Promise((resolve, reject) => {
          this.$axios.get(backendEmployeePendingAccountRequests)
          .then((res) => { resolve(res.data) })
          .catch((e)  => { reject(e) });
        })
      },
      getEmployeeCompletedAccountRequests(page) {
        if(page) {
          var backendEmployeeCompletedAccountRequests = `${process.env.api}account_requests?employee_number=${this.$route.params.id}&status=completed&format=hal&page=${page}`;
        } else {
          var backendEmployeeCompletedAccountRequests = `${process.env.api}account_requests?employee_number=${this.$route.params.id}&status=completed&format=hal`;
        }

        return new Promise((resolve, reject) => {
          this.$axios.get(backendEmployeeCompletedAccountRequests)
          .then((res) => { resolve(res.data) })
          .catch((e)  => { reject(e) });
        })
      },
      onPendingPageChange(page) {
        console.log(page);

        this.currentPage = page;

        this.getEmployeePendingAccountRequests(page)
        .then((res) => {
          this.employeeData.accountRequests.pending.response = res;
        })
        .catch((e)  => {
          this.employeeData.accountRequests.pending.error = e;
        });
      },
      onCompletedPageChange(page) {
        console.log(page);

        this.currentPage = page;

        this.getEmployeeCompletedAccountRequests(page)
        .then((res) => {
          this.employeeData.accountRequests.completed.response = res;
        })
        .catch((e)  => {
          this.employeeData.accountRequests.completed.error = e;
        });
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

::v-deep .tabs {
  ul {
    li {
      &:nth-child(1),
      &:nth-child(2) {
        border-bottom: 1px solid lighten($text-color, 50%) !important;
        padding: 0 0 15px 0 !important;

        &:hover,
        &:focus {
          border-bottom: 1px solid lighten($text-color, 50%) !important;
          padding: 0 0 15px 0 !important;
        }
      }
    }
  }
}

.tab-pane {
  &.employee {
    display: flex;

    svg {
      width: 75px;
      height: 75px;
      margin: 0 20px 0 0;
    }

    .employee-data {
      div {
        margin: 0 0 10px 0;
      }
    }
  }
}

.account-request-tabs {
  &.tabs-group {

    ::v-deep .tabs {
      margin: 0 0 20px 0;
      padding: 0;
      border-bottom: 1px solid lighten($text-color, 50%);
      border-right: none;

      ul {
        display: inline;
        width: 100%;
        list-style-type: none;
        margin: 0;
        padding: 0;
        // border-bottom: 1px solid #ccc;

        li {
          border: 1px solid transparent;
          text-align: center;
          cursor: pointer;
          padding: 8px 16px !important;
          margin: 0 16px -1px 0;
          display: inline-block;
          zoom: 1;

          &.active,
          &:hover {
            background-color: white;
            border-top: 1px solid lighten($text-color, 50%);
            border-right: 1px solid lighten($text-color, 50%);
            border-left: 1px solid lighten($text-color, 50%);
            border-bottom: 1px solid transparent !important;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
          }
        }
      }
    }
  }
}

table {
  margin: 0;

  &.account-requests {
    margin: 0 0 20px 0;

    tbody {
      max-height: calc(100vh - 470px);
      height: auto;
    }
  }
}

table:not(.account-requests) {
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
