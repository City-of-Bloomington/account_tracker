<template>
  <div>
    <pageTitleHeader :page-title="dynamicPageTitle()">

      <fn1-button
        v-if="!responseData"
        slot="buttons"
        class="activate button"
        @click.native="activateAccountRequest()">
        Activate an Account Request
      </fn1-button>
    </pageTitleHeader>

    <main class="page-wrapper">
      <div class="page-description">
        <template v-if="!responseData">
          <p>Activate an <strong>Account Request</strong> for <strong>{{ employeeName }}</strong> by:</p>
          <ol>
            <li>Selecting a Profile</li>
            <li>Answering any required<strong>*</strong> questions</li>
          </ol>
        </template>

        <template v-if="responseData">
          <p>The <strong>Account Request</strong> for <strong>{{ employeeName }}</strong> has been <strong>activated</strong>.</p>
        </template>
      </div>

      <form v-if="!responseData">
        <!-- Profiles -->
        <div class="field-group">
          <label
            for="profile">
            Profile
          </label>
          <select
            name="profile"
            id="profile"
            type="select"
            v-model="activateUser.profile"
            required>
            <option
              v-for="p, i in profiles.profiles"
              :key="p.id"
              :value="p">
              {{ p.name }}
            </option>
          </select>
        </div>

        <!-- Fields for Profile - resource_defaults -->
        <template v-if="activateUser.profile.questions">
          <div
            v-for="f, i in activateUser.profile.questions"
            :key="i"
            class="field-group">

            <template v-if="f.type === 'text'">
              <label :for="i">
                {{ f.label }}
                <template v-if="f.required">*</template>
              </label>

              <input
                v-model="activateUser.questions[`questions[${i}]`]"
                :type="f.type"
                :id="i"
                :name="i"
                :required="f.required" />
            </template>

            <template v-if="f.type === 'tel' || f.type === 'phone'">
              <label :for="i">
                {{ f.label }}
                <template v-if="f.required">*</template>
              </label>

              <input
                v-model="activateUser.questions[`questions[${i}]`]"
                pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                :type="f.type"
                :id="i"
                :name="i"
                :required="f.required" />
            </template>
          </div>
        </template>
      </form>

      <template v-if="responseData">
        <template
          v-for="l, i in responseData._links"
          v-if="i == 'self'">
          <nuxt-link
            class="activate button"
            :to="`/account_requests/${responseData.id}`">
            View the Account Request
          </nuxt-link>
        </template>
      </template>
    </main>
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'
  
  import pageTitleHeader  from '~/components/pageTitleHeader'

  export default {
    components: { pageTitleHeader },
    created() {
      if(!this.employee.employee){
        console.dir('no employee - get it');
        let backendEmployee = `${process.env.backendUrl}employees/${this.$route.params.id}?format=json`;

        this.$axios.get(backendEmployee, { withCredentials: true })
        .then((res) => {
          console.dir('hello');
          this.$store.dispatch('employee/setEmployee', res.data);
        })
        .catch((e)  => {
          this.employeeData.error = e;
          this.$store.dispatch('employee/resetEmployeeState');
        })
      }

      this.getProfiles();
      this.userNumber;
    },
    data () {
      return  {
        activateUser: {
          profile:   {},
          questions: {},
          userId:    '',
        },
        responseData: null,
      }
    },
    watch: {},
    computed: {
      ...mapFields([
        'employee.employee',
        'resources.resources',
        'profiles.profiles'
      ]),
      employeeName(){
        if(this.employee.employee) {
          if(this.employee.employee.firstname || this.employee.employee.lastname){
            return `${this.employee.employee.firstname} ${this.employee.employee.lastname}`
          } else if(this.employee.employee.firstname) {
            return `${this.employee.employee.firstname}`
          } else if(this.employee.employee.lastname) {
            return `${this.employee.employee.lastname}`
          } else {
            return 'No name set ...'
          }
        } else {
          return 'No employee {} set'
        }
      },
      userNumber() {
        if(this.employee.employee)
          return this.activateUser.userId = this.employee.employee.number;
      },
      enableFormSubmitBtn() {
        if(this.activateUser.profile.id)
          return true;
      },
      profileDefaultQuestions() {
        if(this.activateUser.profile)
          return this.activateUser.profile.questions
      }
    },
    methods: {
      activateAccountRequest() {

        let fD = new FormData();
        fD.append(`employee_number`, this.userNumber);
        fD.append(`profile_id`,     this.activateUser.profile.id);
        fD.append('format',         'hal')

        Object.keys(this.activateUser.questions).forEach((item) => {
          fD.append(item, this.activateUser.questions[item])
          // console.log(`${item}: ${this.activateUser.questions[item]}`);
        });

        for(var pair of fD.entries()) {
          console.dir(`${pair[0]}: ${pair[1]}`);
        }

        let createAccountRequestRoute = `${process.env.backendUrl}employees/${this.userNumber}/activate`;

        this.$axios.post(createAccountRequestRoute, fD)
        .then((res) => {
          console.dir(res);
          this.responseData = res.data;
        })
        .catch((e)  => {
          this.responseData = e;
        })
      },
      getProfiles() {
        let backendProfiles = `${process.env.backendUrl}profiles?format=json`;

        this.$axios.get(backendProfiles, { withCredentials: true })
        .then((res) => {
          console.dir(res.data);
          this.$store.dispatch('profiles/setProfiles', res.data);
        })
        .catch((e)  => {
          this.$store.dispatch('profiles/setProfiles');
        })
      },
      dynamicPageTitle() {
        if(this.responseData) {
          return `Employee - ${this.employeeName}: Account Request Activated`
        } else {
          return `Employee - ${this.employeeName}: Activate Account Request`
        }
      }
    }
  }
</script>

<style lang="scss">
ol {
  margin: 20px 0 0 20px;
}

ul {
  margin: 40px 0 0 0;
}
</style>