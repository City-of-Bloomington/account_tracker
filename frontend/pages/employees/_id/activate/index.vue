<template>
  <div>
    <h1>Employee - Activate</h1>

    <section>
      <div class="field-group">
        <label for="department">Department</label>
        <select
          name="department"
          id="department"
          type="select"
          v-model="exampleUser.department">
          <option
            v-for="d, i in departments"
            :key="d.id"
            :value="{id: d.id, name: d.name}">
            {{ d.name }}
          </option>
        </select>
      </div>

      <div
        v-if="exampleUser.department"
        class="field-group">
        <label for="group">Group</label>
        <select
          name="group"
          id="group"
          type="select"
          v-model="exampleUser.group">
          <option
            v-for="g, i in groups"
            :key="g.id"
            :value="{id: g.id, name: g.name}">
            {{ g.name }}
          </option>
        </select>
      </div>

       <div
        v-if="exampleUser.group"
        class="field-group">
        <label for="job">Job</label>
        <select
          name="job"
          id="job"
          type="select"
          v-model="exampleUser.job">
          <option
            v-for="j, i in jobs"
            :key="j.id"
            :value="{id: j.id, name: j.name}">
            {{ j.name }}
          </option>
        </select>
      </div>
    </section>

    <section>
      <div
        v-for="f, i in userDefinition.fields"
        :key="f.code">

        <div class="field-group">
          <template v-if="f.type === 'text'">
            <label :for="f.code">
              {{ f.label }}
            </label>

            <input
              v-model="exampleUser[f.code]"
              :type="f.type"
              :id="f.code"
              :name="f.code"
              :required="f.required" />
          </template>

          <template v-if="f.type === 'tel'">
            <label :for="f.code">
              {{ f.label }}
            </label>

            <input
              v-model="exampleUser[f.code]"
              pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
              :type="f.type"
              :id="f.code"
              :name="f.code"
              :required="f.required" />
          </template>
        </div>
      </div>
    </section>

    <button
      v-if="enableAccountRequestActivationButton"
      @click="activateAccountRequest">
      Activate Account Request
    </button>

    <!-- <template
      v-if="false"
      v-for="r, i in profileResources">
      <h2>Setup - {{ r.name }}</h2>
      <template v-for="f, i in r.definition.fields">
        <div class="field-group">
          <template v-if="f.type === 'text'">
            <label :for="f.code">
              {{ f.label }}
            </label>
            <input
              :type="f.type"
              :id="f.code"
              :name="f.code" />
          </template>

          <template v-if="f.type === 'email'">
            <label :for="f.code">
              {{ f.label }}
            </label>
            <input
              :type="f.type"
              :id="f.code"
              :name="f.code" />
          </template>

          <template v-if="f.type === 'singleChoice'">
            <label :for="f.code">
              {{ f.label }}
            </label>
            <select
              :id="f.code">
              <option
                v-for="o in f.options"
                :value="o"
                :key="o">{{ o }}
              </option>
            </select>
          </template>
        </div>
      </template>
    </template> -->
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'

  export default {
    created() {
      this.getDepts()
      .then((res) => {
        this.departments = res;
      })
      .catch((e)  => {
        console.log(e);
      });
    },
    data () {
      return  {
        departments: null,
        groups:      null,
        jobs:        null,
        userDefinition: {
          fields: [
            {
              label:    "Desktop Phone",
              code:     "phone",
              type:     "tel",
              required: true
            },
            {
              label:    "Public Phone",
              code:     "pager",
              type:     "tel",
              required: true
            },
          ],
        },
        exampleUser: {},
        profileResources: [
          {
            id: 1,
            code: "account_tracker",
            name: "Account Tracker",
            type: "Web Application",
            definition: {
              fields: [
                {
                  label: "First Name",
                  code: "firstname",
                  type: "text",
                  required: true
                },
                {
                  label: "Last Name",
                  code: "lastname",
                  type: "text",
                  required: true
                },
                {
                  label: "Username",
                  code: "username",
                  type: "text",
                  required: true
                },
                {
                  label: "Email",
                  code: "email",
                  type: "email",
                  required: true
                },
                {
                  label: "User Role",
                  code: "userrole",
                  type: "singleChoice",
                  options: [
                    "Employee",
                    "Support Staff",
                    "Administrator"
                  ],
                  required: true
                }
              ],
              actions: {
                create: {
                  url: "https://bloomington.in.gov/account_tracker/users/add",
                  method: "POST"
                },
                changet: {
                  url: "https://bloomington.in.gov/account_tracker/users/update",
                  method: "POST"
                },
                terminate: {
                  url: "https://bloomington.in.gov/account_tracker/users/delete",
                  method: "POST"
                }
              },
              read_from: {
                email: "active_directory",
                lastname: "active_directory",
                username: "active_directory",
                firstname: "active_directory"
              }
            }
          },
          {
            id: 2,
            code: "account_tracker2",
            name: "Account Tracker 2",
            type: "Web Application",
            definition: {
              fields: [
                {
                  label: "First Name",
                  code: "firstname",
                  type: "text",
                  required: true
                },
                {
                  label: "Middle Name",
                  code: "middlename",
                  type: "text",
                  required: true
                },
                {
                  label: "Nickname",
                  code: "nickname",
                  type: "text",
                  required: true
                },
                {
                  label: "Last Name",
                  code: "lastname",
                  type: "text",
                  required: true
                },
                {
                  label: "Username",
                  code: "username",
                  type: "text",
                  required: true
                },
                {
                  label: "Email",
                  code: "email",
                  type: "email",
                  required: true
                },
                {
                  label: "User Role",
                  code: "userrole",
                  type: "singleChoice",
                  options: [
                    "Employee",
                    "Support Staff",
                    "Administrator"
                  ],
                  required: true
                }
              ],
              actions: {
                create: {
                  url: "https://bloomington.in.gov/account_tracker/users/add",
                  method: "POST"
                },
                changet: {
                  url: "https://bloomington.in.gov/account_tracker/users/update",
                  method: "POST"
                },
                terminate: {
                  url: "https://bloomington.in.gov/account_tracker/users/delete",
                  method: "POST"
                }
              },
              read_from: {
                email: "active_directory",
                lastname: "active_directory",
                username: "active_directory",
                firstname: "active_directory"
              }
            }
          },
        ],
      }
    },
    watch: {
      "exampleUser.department.id": {
        handler(val, oldVal) {
          if(val) {
            this.getTimeTrackGroups(val);
          }

          if(val != oldVal) {
            if(this.exampleUser.group) {
              delete this.exampleUser.group;
            }

            if(this.exampleUser.job) {
              delete this.exampleUser.job;
            }
          }
        },
        deep: true,
      },
      "exampleUser.group.id": {
        handler(val, oldVal) {
          if(val) {
            this.getTimeTrackJobs(val);
          }

          if(val != oldVal) {
            if(this.exampleUser.job) {
              delete this.exampleUser.job;
            }
          }
        },
        deep: true,
      }
    },
    computed: {
      ...mapFields([]),
      enableAccountRequestActivationButton() {
        let hasDepartment = this.exampleUser.department,
            hasGroup      = this.exampleUser.group,
            hasJob        = this.exampleUser.job,
            hasPhone      = this.exampleUser.phone,
            hasPager      = this.exampleUser.pager;

        if(hasDepartment  &&
           hasGroup       &&
           hasJob         &&
           hasPhone       &&
           hasPager
          ) {
          return true
        }
      }
    },
    methods: {
      getTimeTrackGroups(id) {
        this.getGroups(id)
        .then((res) => {
          this.groups = res;
        })
        .catch((e)  => {
          console.log(e);
        });
      },
      getTimeTrackJobs(id) {
        this.getJobs(id)
        .then((res) => {
          this.jobs = res;
        })
        .catch((e)  => {
          console.log(e);
        });
      },
      activateAccountRequest() {
        alert('woot')
      }
    }
  }
</script>

<style>
li {
  margin: 0 0 10px 0;
}
</style>
