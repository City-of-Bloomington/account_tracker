<template>
   <div>
    <pageTitleHeader
      :page-title="`Account Request: ${employeeName}`">
      
      <template v-if="accountRequestData.response">
        <fn1-button-group
          v-if="accountRequestData.response._links"
          slot="buttons">
            <fn1-button
              class="activate">
              Activate
            </fn1-button>

          <template v-for="l, i in accountRequestData.response._links">
            <fn1-button
              class="change"
              v-if="i === 'edit'"
              @click.native="updateAccountRequest()">
              Save / Update
            </fn1-button>

            <fn1-button
              class="delete"
              v-if="i === 'delete'"
              @click.native="openModal('deleteAccountRequestModal')">
              Delete
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

      <form @submit.prevent>
        <template v-if="update.accountRequest.employee">
          <h3>Employee</h3>
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
      let backendAccountRequest = `${process.env.backendUrl}account_requests/${this.$route.params.id}?format=hal`;

      this.$axios.get(backendAccountRequest)
      .then((res) => {
        this.accountRequestData.response = res.data;
        //below bc res is mutable
        this.update.accountRequest       = JSON.parse(JSON.stringify(res.data))
      })
      .catch((e)  => {
        this.accountRequestData.error = e;
      });
    },
    data(){
      return {
        accountRequestData: {
          response: null,
          error:    null,
        },
        update:     {
          errors:         null,
          accountRequest: {}
        },
        deleted:    {
          message: null,
        }
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
    },
    methods: {
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

    p {
      &:last-of-type {
        margin-left: auto;
      }
    }
  }
</style>