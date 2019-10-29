<template>
  <div>
    <h1 class="page-title">
      Employee
      <template v-if="employee.employee">
        - {{ employee.employee.firstname }} {{ employee.employee.lastname }}
      </template>
    </h1>

    <fn1-button-group>
      <fn1-button
        class="activate"
        @click.native="activateAccountRequest()">
        Activate
      </fn1-button>

      <fn1-button
        class="change"
        @click.native="changeAccountRequest()">
        Change
      </fn1-button>

      <fn1-button
        class="terminate"
        @click.native="terminateAccountRequest()">
        Terminate
      </fn1-button>
    </fn1-button-group>

    <template v-if="employee.resources">
      <div v-for="resource, i in employee.resources">
        <h3>{{ i }}</h3>
        <pre>{{ resource}}</pre>
      </div>
    </template>

    <!-- <nuxt-link
      to="/employees/1/activate">
      Activate User
    </nuxt-link> -->
  </div>
</template>

<script>
  import {
    mapFields }       from 'vuex-map-fields'
  import axios        from 'axios'

  export default {
    validate ({ params }) {
      return /^\d+$/.test(params.id)
    },
    created() {
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
    },
    data () {
      return  {
        employeeData: {
          response: '',
          error:    '',
        },
      }
    },
    computed: {
      ...mapFields(['employee.employee'])
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
      }
    }
  }
</script>

<style lang="scss">
pre {
  background-color: $color-grey-lighter;
  color: $text-color;
}

li {
  margin: 0 0 10px 0;
}

button {
  padding: 5px 10px;
  border: none !important;
  letter-spacing: .5px;

  &.activate {
    background-color: $color-green;
  }

  &.change {
    // color: ;
    background-color: darken($color-orange-dark, 15%);
  }

  &.terminate {
    background-color: $color-red;
  }
}
</style>
