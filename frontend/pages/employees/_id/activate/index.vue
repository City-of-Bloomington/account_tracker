<template>
  <div>
    <h1 class="page-title">
      Employee: Activate
      <template v-if="employee.employee">
        - {{ employee.employee.firstname }} {{ employee.employee.lastname }}
      </template>
    </h1>

    <form>
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
          v-model="exampleUser.profile"
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
      <template v-if="exampleUser.profile.resource_defaults">
        <div
          v-for="f, i in profileDefaultQuestions"
          :key="i"
          class="field-group">

          <template v-if="f.type === 'text'">
            <label :for="i">
              {{ f.label }}
              <template v-if="f.required">*</template>
            </label>

            <input
              v-model="exampleUser.questions[i]"
              :type="f.type"
              :id="i"
              :name="i"
              :required="f.required" />
          </template>

          <template v-if="f.type === 'tel'">
            <label :for="i">
              {{ f.label }}
              <template v-if="f.required">*</template>
            </label>

            <input
              v-model="exampleUser.questions[i]"
              pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
              :type="f.type"
              :id="i"
              :name="i"
              :required="f.required" />
          </template>
        </div>
      </template>

      <button
        v-if="enableFormSubmitBtn"
        type="submit"
        @click.prevent="activateAccountRequest()">
        Activate {{ employee.employee.firstname }}'s Account Request
      </button>
    </form>

    <ul v-if="examplePostUser">
      <li v-for="f, i in examplePostUser">
        <strong>{{ i }} :: </strong>{{f}}
      </li>
    </ul>
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'

  export default {
    created() {
      // this.getDepts()
      // .then((res) => {
      //   this.departments = res;
      // })
      // .catch((e)  => {
      //   console.log(e);
      // });

      // this.getResources();


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
      this.userNumber
    },
    data () {
      return  {
        // activeDirectoryCode: 'active_directory',
        // activeDirectoryResource: null,
        // departments: null,
        // groups:      null,
        // jobs:        null,
        resourcesData:  {
          response: null,
          error:    null
        },
        exampleUser: {
          profile:   {},
          questions: {},
          userId:    '',
        },
        examplePostUser: {},
      }
    },
    watch: {
      // "exampleUser.profile": {
      //   handler(val, oldVal) {
      //     if(val) {
      //       // this.getTimeTrackJobs(val);
      //       this.$router.push(
      //         {
      //           query: { 'profile': this.exampleUser.profile.id }
      //         }
      //       );
      //     }

      //     // if(val != oldVal) {
      //     //   if(this.exampleUser.job) {
      //     //     delete this.exampleUser.job;
      //     //   }
      //     // }
      //   },
      //   deep: true,
      // }
      // "exampleUser.department.id": {
      //   handler(val, oldVal) {
      //     if(val) {
      //       this.getTimeTrackGroups(val);
      //     }

      //     if(val != oldVal) {
      //       if(this.exampleUser.group) {
      //         delete this.exampleUser.group;
      //       }

      //       if(this.exampleUser.job) {
      //         delete this.exampleUser.job;
      //       }
      //     }
      //   },
      //   deep: true,
      // },
      // "exampleUser.group.id": {
      //   handler(val, oldVal) {
      //     if(val) {
      //       this.getTimeTrackJobs(val);
      //     }

      //     if(val != oldVal) {
      //       if(this.exampleUser.job) {
      //         delete this.exampleUser.job;
      //       }
      //     }
      //   },
      //   deep: true,
      // }
    },
    computed: {
      ...mapFields([
        'employee.employee',
        'resources.resources',
        'profiles.profiles'
      ]),
      userNumber() {
        if(this.employee.employee)
          return this.exampleUser.userId = this.employee.employee.number;
      },
      enableFormSubmitBtn() {
        if(this.exampleUser.profile.id)
          return true;
      },
      profileDefaultQuestions() {
        if(this.exampleUser.profile)
          return this.exampleUser.profile.resource_defaults.questions
      }
      // activeDirectoryResourceDepartments() {
      //   // NOTE OLD - NOT NEEDED
      //   if(this.resources) {
      //     this.activeDirectoryResource = this.resources.filter((r) => {
      //       return r.code === this.activeDirectoryCode;
      //     });

      //     return this.activeDirectoryResource[0].fields.department
      //   }
      // },
    },
    methods: {
      // getTimeTrackGroups(id) {
      //   this.getGroups(id)
      //   .then((res) => {
      //     this.groups = res;
      //   })
      //   .catch((e)  => {
      //     console.log(e);
      //   });
      // },
      // getTimeTrackJobs(id) {
      //   this.getJobs(id)
      //   .then((res) => {
      //     this.jobs = res;
      //   })
      //   .catch((e)  => {
      //     console.log(e);
      //   });
      // },
      activateAccountRequest() {

        let fD = new FormData();
        // fD.append(...this.exampleUser.questions);
        fD.append(`employee.number`, this.userNumber);
        fD.append(`profile.id`, this.exampleUser.profile.id);

        this.examplePostUser = {
          ...this.exampleUser.questions,
          "employee.number": this.userNumber,
          "profile.id":      this.exampleUser.profile.id,
        };

        console.dir(this.examplePostUser)
      },
      // getResources() {
      //   // OLD - NOTE NEEDED
      //   let backendResources = `${process.env.backendUrl}resources?format=json`;

      //   this.$axios.get(backendResources, { withCredentials: true })
      //   .then((res) => {
      //     console.dir(res.data);
      //     this.$store.dispatch('resources/setResources', res.data);
      //   })
      //   .catch((e)  => {
      //     this.resourcesData.error = e;
      //     this.$store.dispatch('resources/resetResourcesState');
      //   })
      // }
      getProfiles() {
        let backendProfiles = `${process.env.backendUrl}profiles?format=json`;

        this.$axios.get(backendProfiles, { withCredentials: true })
        .then((res) => {
          console.dir(res.data);
          this.$store.dispatch('profiles/setProfiles', res.data);
        })
        .catch((e)  => {
          this.resourcesData.error = e;
          this.$store.dispatch('profiles/setProfiles');
        })
      }
    }
  }
</script>

<style lang="scss">

ul {
  margin: 40px 0 0 0;
  li {
    margin: 0 0 10px 0;
  }
}
</style>