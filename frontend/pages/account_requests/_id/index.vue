<template>
   <div>
    <pageTitleHeader
      :page-title="`Account Request: ${employeeName}`">
      
      <template v-if="accountRequestData.response">
        <fn1-button-group
          v-if="accountRequestData.response._links"
          slot="buttons">
          <template v-for="l, i in accountRequestData.response._links">
            <fn1-button
              class="activate"
              v-if="i === 'edit'"
              @click.native="updateAccountRequest()">
              Save Account Request
            </fn1-button>

            <fn1-button
              class="delete"
              v-if="i === 'delete'"
              @click.native="openModal('deleteAccountRequestModal')">
              Delete Account Request
            </fn1-button>
          </template>
        </fn1-button-group>
      </template>
    </pageTitleHeader>

    <main class="page-wrapper">
      <div class="page-description">
        <p>Viewing <strong>{{ employeeName }}'s Account Request</strong>.</p>

        <p v-if="accountRequestData.response">
          <strong>Last Modified:</strong>
          {{ $moment(accountRequestData.response.modified.date).format('MM/DD/YYYY') }} -
          {{ $moment(accountRequestData.response.modified.date).fromNow() }}
        </p>
      </div>

      <fn1-alert variant="warning" v-if="update.errors">
        <template v-for="e, i in update.errors">
          <strong>Error:</strong> {{ e }}
        </template>
      </fn1-alert>

      <!-- <template
        v-if="accountRequestData.response"
        v-for="r, i in accountRequestData.response.resources">
        {{r}}<br><br><br>---<br><br><br>
      </template> -->

      <fn1-tabs class="vertical-left" v-if="employeeData">
        <fn1-tab name="Employee" selected="true">
          <form @submit.prevent>
            <template v-if="update.accountRequest.employee">
              <fn1-number
                v-model="update.accountRequest.employee.number"
                label="Employee Number"
                placeholder="Employee Number"
                name="number"
                id="number" />

              <fn1-input
                v-model="update.accountRequest.employee.firstname"
                label="First Name"
                placeholder="First Name"
                name="firstname"
                id="firstname" />

              <fn1-input
                v-model="update.accountRequest.employee.lastname"
                label="Last Name"
                placeholder="Last Name"
                name="lastname"
                id="lastname" />

              <fn1-input
                disabled
                v-model="update.accountRequest.employee.department"
                label="Department"
                placeholder="Department"
                name="department"
                id="department" />
            </template>
          </form>
        </fn1-tab>

        <fn1-tab
          v-if="accountRequestData.response"
          v-for="r, i in accountRequestData.response.resources"
          :key="i"
          :name="resourceName(i)">
          <div>

            <div class="resource-header">
              <div>
                Name: {{ resourceName(i) }}
                <!-- <p><strong>Requested Values:</strong> <strong>Account Request</strong> values for this <strong>Resource</strong>.</p> -->
                <!-- <p><strong>Current Values:</strong> Production values for this <strong>Resource</strong>.</p> -->

                <div class="legend">
                  <div class="difference">
                    = value difference
                  </div>
                </div>
              </div>
            
              <fn1-button
                class="change">
                Apply Requested Values
              </fn1-button>
            </div>

            <table>
              <caption class="sr-only">
                {{ i }} Resource Table
              </caption>

              <thead>
                <tr>
                  <th scope="col">Fields</th>
                  <th scope="col">Account Request Values</th>
                  <th scope="col">Production Values</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="res_v, res_i in r">
                  <th scope="row">
                    <strong>{{ res_i }}</strong>
                  </th>

                  <td>
                    <fn1-input
                      v-if="res_v"
                      v-model="update.accountRequest.resources[i][res_i]"
                      :label="res_i"
                      :placeholder="res_v"
                      :name="res_i"
                      :id="res_i" />

                    <fn1-input
                      v-else
                      v-model="update.accountRequest.resources[i][res_i]"
                      :label="res_i"
                      :placeholder="res_i"
                      :name="res_i"
                      :id="res_i" />
                  </td>

                  <td>
                    <template v-if="employeeResources">
                      <template v-for="er, er_i in employeeResources">
                        <template v-if="er.definition.code == i">
                          <template v-for="v, emp_i in er.values">
                            <template v-if="emp_i == res_i">
                              <span :class="{'diff': res_v != v }">{{ v }}</span>
                            </template>
                          </template>
                        </template>
                      </template>
                    </template>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </fn1-tab>
      </fn1-tabs>
    </main>

    <exampleModal
      id="delete-account-request-modal"
      ref="deleteAccountRequestModal"
      :title="`Delete: Account Request`">

      <fn1-alert
        slot="body"
        v-if="!deleted.message"
        variant="danger">
        <p><strong>Delete this Account Request?</strong></p>
      </fn1-alert>

      <p
        slot="body"
        v-if="deleted.message"
        class="message success">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#4cae4f" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" class=""></path></svg>
        <strong>{{ deleted.message }}</strong>
      </p>

      <ul
        slot="body"
        v-if="accountRequestData.response && !deleted.message">

        <li>
          <strong>Status:</strong> {{ accountRequestData.response.status }}
        </li>

        <li>
          <strong>Type:</strong> {{ accountRequestData.response.type }}
        </li>

        <li v-if="accountRequestData.response.created">
          <strong>Created:</strong> 
           {{ $moment(accountRequestData.response.created.date).format('MM/DD/YYYY') }} <small>({{ $moment(accountRequestData.response.created.date).fromNow() }})</small>
        </li>

        <li>
          <strong>Name:</strong>  {{ employeeName }} <small>(ID: {{ accountRequestData.response.employee_number }})</small>
        </li>

        <li>
          <strong>Dept.:</strong> {{ accountRequestData.response.employee.department }}
        </li>
      </ul>

      <fn1-button
        slot="footer"
        class="activate"
        title="Confirm"
        @click.native="deleteAccountRequest()">Confirm (Delete)
      </fn1-button>

      <fn1-button
        slot="footer"
        class="cancel"
        title="Cancel"
        @click.native="cancelModal('deleteAccountRequestModal')">Cancel
      </fn1-button>
    </exampleModal>
   </div>
</template>

<script>
  import { mapFields }    from 'vuex-map-fields'
  import axios            from 'axios'

  import pageTitleHeader  from '~/components/pageTitleHeader'
  import exampleModal     from '~/components/design-system/exampleModal'

  export default {
    components: { pageTitleHeader, exampleModal },
    validate ({ params }) {
      return /^\d+$/.test(params.id)
    },
    created() {
      this.getAccountRequestById(this.$route.params.id)
      .then((res) => {
        this.accountRequestData.response = res;
        //below bc res is mutable
        this.update.accountRequest       = JSON.parse(JSON.stringify(res));

        this.getEmployeeData(res.employee_number)
        .then((res) => {
          console.dir('employee res');
          this.employeeData = res;
        })
        .catch((e)  => {
          console.dir('employee err')
        })
      })
      .catch((e)  => {
        this.accountRequestData.error    = e;
      });

      this.getResources()
      .then((res) => {
         console.dir('resources res');
        this.resources.response = res;
      })
      .catch((e)  => {
         console.dir('resources err');
        this.resources.error    = e;
      });
    },
    data(){
      return {
        employeeData:       null,
        accountRequestData: {
          response:         null,
          error:            null,
        },
        update:     {
          errors:           null,
          accountRequest:   {}
        },
        deleted:    {
          message:          null,
        },
        resources:  {
          response:         null,
          error:            null,
        },
      }
    },
    computed: {
      employeeName(){
        if(this.accountRequestData.response) {
          if(this.accountRequestData.response.employee.firstname || this.accountRequestData.response.employee.lastname){
            return `${this.accountRequestData.response.employee.firstname} ${this.accountRequestData.response.employee.lastname}`
          } else if(this.accountRequestData.response.employee.firstname) {
            return `${this.accountRequestData.response.employee.firstname}`
          } else if(this.accountRequestData.response.employee.lastname) {
            return `${this.accountRequestData.response.employee.lastname}`
          } else {
            return 'No name set ...'
          }
        } else {
          return 'No employee {} set'
        }
      },
      filteredServices() {
        // if(this.employeeData._embedded.resources.length) {
        //   return this.employeeData._embedded.resources
        //   .filter((r) => {
        //     let rName   = r.definition.name.toLowerCase();
        //     return rName
        //   })
        //   .sort((a, b) => {
        //     let nameA = a.definition.name.toUpperCase(),
        //     nameB     = b.definition.name.toUpperCase();
        //     return (nameA < nameB) ? -1 : (nameA > nameB) ? 1 : 0;
        //   });
        // }
        if(this.accountRequestData) {
          return this.accountRequestData.response.resources
          // .filter((r) => {
          //   let rName   = r.definition.name.toLowerCase();
          //   return rName
          // })
          // .sort((a, b) => {
          //   let nameA = a.definition.name.toUpperCase(),
          //   nameB     = b.definition.name.toUpperCase();
          //   return (nameA < nameB) ? -1 : (nameA > nameB) ? 1 : 0;
          // });
        }
      },
      employeeResources() {
        if(this.employeeData)
          return this.employeeData._embedded.resources
      },
    },
    methods: {
      resourceName(resourceCode) {
        if(this.employeeResources.length) {
          let name = this.employeeResources.filter((r) => {
            if(r.definition.code == resourceCode) {
              return r
            }
          });

          let result = name.map(({ definition }) => definition.name)

          return result[0]
        }
      },
      deleteAccountRequest() {
        let backendAccountRequestDelete = `${process.env.backendUrl}account_requests/${this.$route.params.id}/delete?format=hal`;

        this.$axios.post(backendAccountRequestDelete)
        .then((res) => {

          this.deleted.message = "Success";
          document.getElementsByClassName('modal-footer')[0].style.display = "none";
          setTimeout(() => {
            this.$refs.deleteAccountRequestModal.showModal = false;
            this.$router.push({ path: "/account_requests/" });
          }, 1500);
        })
        .catch((e)  => {
          console.dir(e)
        })
      },
      updateAccountRequest() {
        let backendAccountRequestUpdate = `${process.env.backendUrl}account_requests/update/${this.$route.params.id}?format=json`,
                                     fD = new FormData();

        fD.append(`id`,               this.update.accountRequest.id),
        fD.append(`requester_id`,     this.update.accountRequest.requester_id),
        fD.append('employee_number',  this.update.accountRequest.employee_number),
        fD.append('type',             this.update.accountRequest.type),
        fD.append('status',           this.update.accountRequest.status),
        fD.append('employee',         JSON.stringify(this.update.accountRequest.employee)),
        fD.append('resources',        JSON.stringify(this.update.accountRequest.resources));

        console.dir('update Account Req. Form Data')
        for(var pair of fD.entries()) {
          console.dir(`${pair[0]}: ${pair[1]}`);
        }

        this.$axios.post(backendAccountRequestUpdate, fD)
        .then((res) => {
          console.dir(res)
          
          if(res.data.errors){
            console.dir('ERRORS')
            this.update.errors = res.data.errors;
          }

          let backendAccountRequest = `${process.env.backendUrl}account_requests/${this.$route.params.id}?format=hal`;

          this.$axios.get(backendAccountRequest)
          .then((res) => {
            this.accountRequestData.response = res.data;
          })
          .catch((e)  => {
            this.accountRequestData.error = e;
          });
        })
        .catch((e)  => {
          console.dir('update failed');
          console.dir(e)
        })
      },
      getEmployeeData(id) {
        return new Promise((resolve, reject) => {
          let backendEmployee = `${process.env.backendUrl}employees/${id}?format=hal`;

          this.$axios.get(backendEmployee)
          .then((res) => { resolve(res.data) })
          .catch((e)  => { reject(e) });
        })
      },
      getAccountRequestById(id) {
        return new Promise((resolve, reject) => {
          let backendAccountRequest = `${process.env.backendUrl}account_requests/${id}?format=hal`;

          this.$axios.get(backendAccountRequest)
          .then((res) => { resolve(res.data) })
          .catch((e)  => { reject(e) });
        })
      },
      getResources() {
        return new Promise((resolve, reject) => {
          let backendResources = `${process.env.backendUrl}resources?format=hal`;

          this.$axios.get(backendResources)
          .then((res) => { resolve(res.data) })
          .catch((e)  => { reject(e) });
        })
      },
      openModal(modalRef) {
        if(modalRef === 'deleteAccountRequestModal'){
          this.$refs.deleteAccountRequestModal.showModal = true;
        }
      },
      cancelModal(modalRef) {
        if(modalRef === 'deleteAccountRequestModal'){
          this.$refs.deleteAccountRequestModal.showModal = false;
        }
      },
      selectedResource(resource, index) {
        return index == 0;
      }
    }
  }
</script>

<style lang="scss" scoped>
  .button-group {
    button,
    .button {
      border-radius: $radius-default !important;
      margin-left: 10px !important;
    }
  }

  .page-description {
    display: flex;
    padding: 0 0 20px 0;
    margin: 20px 0;
    border-bottom: 1px solid lighten($text-color, 50%);

    p {
      &:last-of-type {
        margin-left: auto;
      }
    }
  }

  .resource-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    border-bottom: 1px solid lighten($text-color, 50%);
    margin: 0 0 15px 0;
    padding: 0 0 15px 0;

    div {
      &:nth-child(1) {
      }
    }

    h2 {
      color: $text-color;
      margin: 0 0 10px 0;
    }

    h2, p {
      width: fit-content;
    }

    p {
      margin: 0 0 15px 0;
    }

    button {
      margin-left: auto;
      margin-right: 0;
    }
  }

  .diff {
    background-color: #ffe6a6;
    padding: 5px 8px;
    border-radius: $radius-default;
    line-height: 28px;
  }

  table {
    // background-color: purple;

    ::v-deep .field-group {
      label {
        border: 0;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
      }

      input {
        text-align: right;
      }
    }

    

    thead {
      tr {
        th {
          &:nth-child(1) {
            // background-color: blue;
            width: 150px;
          }

          &:nth-child(2) {
            // background-color: red;
            width: 400px;
            text-align: right;
          }

          &:nth-child(3) {
            text-align: left;
            border-left: 1px solid lighten($text-color, 50%);
          }
        }
      }
    }

    tbody {
      tr {
        th, td {
          &:nth-child(1) {
            // background-color: blue;
            width: 150px;
          }

          &:nth-child(2) {
            // background-color: red;
            width: 400px;
            text-align: right;
          }

          &:nth-child(3) {
            text-align: left;
            border-left: 1px solid lighten($text-color, 50%);
          }
        }
      }
    }
  }
</style>