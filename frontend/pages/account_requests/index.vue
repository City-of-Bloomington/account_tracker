<template>
  <div>
    <pageTitleHeader page-title="Account Requests" />

    <main class="page-wrapper">
      <div class="page-description">
        <p><strong>Account Requests</strong> in the system.</p>
      </div>

      <fn1-tabs>
        <fn1-tab
          :name="`Pending (${accountRequestsPendingLength})`"
          :selected="true">

          <header class="table-header">
            <form
              id="pending-search"
              class="inline">
              <fn1-input
                label="First Name"
                placeholder="First Name"
                name="firstname"
                id="firstname" />

              <fn1-input
                label="Last Name"
                placeholder="Last Name"
                name="lastname"
                id="lastname" />
              
              <fn1-button-group>
                <fn1-button
                  class="search icon">
                  search
                </fn1-button>

                <fn1-button
                  class="cancel icon">
                  clear
                </fn1-button>
              </fn1-button-group>
            </form>

            <examplePagination
              v-if="accountRequests.data"
              :total="accountRequests.data.total"
              :per-page="accountRequests.data.itemsPerPage"
              :current-page="currentPage"
              @pagechanged="onPageChange" />
          </header>

          <template v-if="accountRequests.pending">
            <table class="fixed-header">
              <caption class="sr-only">
                Pending Account Requests Table
              </caption>

              <col width="150">

              <thead>
                <tr>
                  <th scope="col">Created Date</th>
                  <th scope="col">Name</th>
                  <th scope="col">Requester</th>
                  <th scope="col">Type</th>
                  <th scope="col">Status</th>
                  <th scope="col">Department</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>

              <tbody>
                <template v-for="ar, i in accountRequests.pending">
                  <tr>
                    <th scope="row">
                      <template v-if="ar.created.date">
                        {{ $moment(ar.created.date).format('MM/DD/YYYY') }}<br>
                        <!-- <small>{{ $moment(ar.created.date).fromNow() }}</small><br> -->
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
                      <template v-if="ar.status">
                        {{ ar.status }}
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
          </template>

          <template v-else>
            <p>No <strong>Pending Account Requests</strong>.</p>
          </template>
        </fn1-tab>

        <fn1-tab :name="`Complete (${accountRequestsCompleteLength})`">
          <template v-if="accountRequests.complete">
            <!-- <p><strong>Complete Account Requests:</strong> {{ accountRequests.complete.length }}</p> -->
            
            <header class="table-header">
              <form
                id="pending-search"
                class="inline">
                <fn1-input
                  label="First Name"
                  placeholder="First Name"
                  name="firstname"
                  id="firstname" />

                <fn1-input
                  label="Last Name"
                  placeholder="Last Name"
                  name="lastname"
                  id="lastname" />
                
                <fn1-button-group>
                  <fn1-button
                    class="search icon">
                    search
                  </fn1-button>

                  <fn1-button
                    class="cancel icon">
                    clear
                  </fn1-button>
                </fn1-button-group>
              </form>

              <!-- <examplePagination
                v-if="accountRequests.data"
                :total="accountRequests.data.total"
                :per-page="accountRequests.data.itemsPerPage"
                :current-page="currentPage"
                @pagechanged="onPageChange" /> -->
            </header>

            <template v-if="accountRequests.complete">
              <table class="fixed-header">
                <caption class="sr-only">
                  Completed Account Requests Table
                </caption>

                <col width="150">

                <thead>
                  <tr>
                    <th scope="col">Created Date</th>
                    <th scope="col">Name</th>
                    <th scope="col">Requester</th>
                    <th scope="col">Type</th>
                    <th scope="col">Status</th>
                    <th scope="col">Department</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>

                <tbody>
                  <template v-for="ar, i in accountRequests.complete">
                    <tr>
                      <th scope="row">
                        <template v-if="ar.created.date">
                          {{ $moment(ar.created.date).format('MM/DD/YYYY') }}<br>
                          <!-- <small>{{ $moment(ar.created.date).fromNow() }}</small><br> -->
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
                        <template v-if="ar.status">
                          {{ ar.status }}
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
            </template>
          </template>

          <template v-else>
            <p>No <strong>Complete Account Requests</strong>.</p>
          </template>
        </fn1-tab>
      </fn1-tabs>
    </main>
  </div>
</template>

<script>
import {
  mapFields }             from 'vuex-map-fields'

import pageTitleHeader    from '~/components/pageTitleHeader'
import examplePagination  from '~/components/design-system/examplePagination'
  
export default {
  head () {
    return {
      title: `${process.env.appName}: Acct. Requests`,
    }
  },
  components: { pageTitleHeader, examplePagination },
  created() {
    this.getAccountRequests()
    .then((res) => {
      this.accountRequests.data = res;
      this.accountRequests.latest = res;
      this.accountRequestsPending();
      this.accountRequestsComplete();
    })
    .catch((e)  => {
      this.accountRequests.errrors = e;
    })
  },
  data() {
    return {
      currentPage: 1,
      accountRequests: {
        data:     null,
        errors:   null,
        pending:  null,
        complete: null,
        latest:   null,
      }
    }
  },
  computed: {
    accountRequestsData() {
      if(this.accountRequests.data) {
        return this.accountRequests.data._embedded.account_requests
      } else {
        return false;
      }
    },
    accountRequestsPendingLength() {
      if(this.accountRequests.latest) {
        let pending     = this.accountRequests.latest._embedded.account_requests.filter(({ status }) => status == 'pending');

        return pending.length
      }
    },
    accountRequestsCompleteLength() {
      if(this.accountRequests.latest) {
        let complete     = this.accountRequests.latest._embedded.account_requests.filter(({ status }) => status == 'completed');

        return complete.length
      }
    },
  },
  methods: {
    getAccountRequests(page) {

      if(page) {
        var accountRequestsRoute = `${process.env.backendUrl}account_requests?format=hal&page=${page}`;
      } else {
        var accountRequestsRoute = `${process.env.backendUrl}account_requests?format=hal`;
      }

      console.dir('getAccountRequests');
      console.dir(page)
      console.dir(accountRequestsRoute);

      return new Promise((resolve, reject) => {
        this.$axios.get(accountRequestsRoute)
        .then((res) => { resolve(res.data) })
        .catch((e)  => { reject(e) });
      })
    },
    accountRequestsPending() {
      if(this.accountRequests.data){
        this.accountRequests.pending = this.accountRequests.data._embedded.account_requests
        .filter(({ status }) => status == 'pending');
      }
    },
    accountRequestsComplete() {
      if(this.accountRequests.data){
        this.accountRequests.complete = this.accountRequests.data._embedded.account_requests
        .filter(({ status }) => status == 'completed');
      }
    },
    onPageChange(page) {
      console.log(page)
      this.currentPage = page;

      this.getAccountRequests(page)
      .then((res) => {
        this.accountRequests.data = res;
        this.accountRequestsPending();
        this.accountRequestsComplete();
      })
      .catch((e)  => {
        this.accountRequests.errrors = e;
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  small {
    font-size: 14px;
  }

  header {
    &.table-header {
      display: flex;
      align-items: flex-end;
    }
  }

  nav {
    &.pagination {
      margin-left: auto;
    }
  }

  table {
    &.fixed-header {
      tbody {
        height: calc(100vh - 450px);
      }
    }
  }
</style>