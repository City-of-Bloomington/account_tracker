<template>
  <div>
    <exampleBreadCrumbs />

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
      <div class="page-description" v-if="!responseData">
        <p>Activate an <strong>Account Request</strong> for <strong>{{ employeeName }}</strong> by:</p>
        <ol>
          <li>Selecting a Profile</li>
          <li>Answering any required<strong>*</strong> questions</li>
        </ol>
      </div>

      <div v-if="responseData" class="response-success">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="rgb(67, 152, 70)" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4zm323-128.4l-27.8-28.1c-4.6-4.7-12.1-4.7-16.8-.1l-104.8 104-45.5-45.8c-4.6-4.7-12.1-4.7-16.8-.1l-28.1 27.9c-4.7 4.6-4.7 12.1-.1 16.8l81.7 82.3c4.6 4.7 12.1 4.7 16.8.1l141.3-140.2c4.6-4.7 4.7-12.2.1-16.8z" class=""></path></svg>

        <p>The <strong>Account Request</strong> for <strong>{{ employeeName }}</strong> has been <strong>activated</strong>.</p>

        <!-- The backend stopped returning response data -_-, --> 
        <!--<template
          v-for="l, i in responseData._links"
          v-if="i == 'self'">
          <nuxt-link
            class="activate button"
            :to="`/account_requests/${responseData.id}`">
            View the Account Request
          </nuxt-link>
        </template>-->
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
    </main>
  </div>
</template>

<script>
  import { mapFields }      from 'vuex-map-fields'
  
  import pageTitleHeader    from '~/components/pageTitleHeader'
  import exampleBreadCrumbs from '~/components/design-system/exampleBreadCrumbs'


  export default {
    components: { pageTitleHeader, exampleBreadCrumbs },
    created() {
      if(!this.employee.employee){
        console.dir('no employee - get it');
        let backendEmployee = `${process.env.api}employees/${this.$route.params.id}?format=json`;

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
        fD.append(`employee_number`,  this.userNumber);
        fD.append(`profile_id`,       this.activateUser.profile.id);
        fD.append('format',           'hal')

        Object.keys(this.activateUser.questions).forEach((item) => {
          fD.append(item, this.activateUser.questions[item])
          // console.log(`${item}: ${this.activateUser.questions[item]}`);
        });

        for(var pair of fD.entries()) {
          console.dir(`${pair[0]}: ${pair[1]}`);
        }

        let createAccountRequestRoute = `${process.env.api}employees/${this.userNumber}/activate`;

        this.$axios.post(createAccountRequestRoute, fD)
        .then((res) => {
          console.dir(res);

          // we don't get a proper response from the backend, sigh -_-,,
          // eg, this.responseData = res.data;
          // so, static text'it
          this.responseData = 'Account Request successfully initialized.'
        })
        .catch((e)  => {
          this.responseData = e;
        })
      },
      getProfiles() {
        this.$axios.get(`${process.env.api}${process.env.apiProfiles}`)
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
          return `Account Request Activated: ${this.employeeName}`
        } else {
          return `Activate Account Request: ${this.employeeName}`
        }
      }
    }
  }
</script>

<style lang="scss">
.page-description {
  flex-wrap: wrap;

  p {
    width: 100%;
  }

  ul, ol {
    margin: 20px 0 0 20px;
  }
}

.response-success {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;

  p {
    width: 100%;
    text-align: center;
  }

  svg {
    width: 100px;
    height: 100px;
    margin: 0 0 20px 0;
  }
}
</style>