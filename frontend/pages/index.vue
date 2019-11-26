<template>
  <div>
    <exampleBreadCrumbs />
    <div class="dashboard">
      <div class="card">
        <header>
          <h1>Overall Account Requests by %</h1>
        </header>

        <div class="chart-container">
          <doughnut-chart
            v-if="chartdata.datasets[0].data.length"
            :chartdata="chartData"
            :options="chartOptions" />
        </div>
      </div>
      
      <div class="card" v-if="accountRequests.pending.response">
        <header>
          <h1>Account Requests: Latest Pending</h1>
        </header>

        <ul>
          <li v-for="ar, i in accountRequests.pending.response._embedded.account_requests"
              v-if="i <= 4">
            <div>
              <span v-if="ar.employee.firstname || ar.employee.lastname">
                {{ getUserPhoto(ar.employee.username) }}
                <strong>
                  <template v-if="ar.employee.firstname">
                    {{ ar.employee.firstname }}
                  </template>
                  <template v-if="ar.employee.lastname">
                    {{ ar.employee.lastname }}
                  </template>
                </strong>
              </span><br>

              <span v-if="ar.employee.department">
                <small>{{ ar.employee.department }}</small>
              </span>
            </div>

            <div>
              <template v-for="l, i in ar._links">
                <template v-if="i == 'self'">
                  <nuxt-link
                    class="button"
                    :to="`/account_requests/${ar.id}`">
                    View
                  </nuxt-link>
                </template>
              </template>
            </div>
          </li>
        </ul>
      </div>

      <div class="card" v-if="accountRequests.completed.response">
        <header>
          <h1>Account Requests: Latest Completed</h1>
        </header>

        <ul>
          <li v-for="ar, i in accountRequests.completed.response._embedded.account_requests"
              v-if="i <= 4">
            <div>
              <span v-if="ar.employee.firstname || ar.employee.lastname">
                <strong>
                  <template v-if="ar.employee.firstname">
                    {{ ar.employee.firstname }}
                  </template>
                  <template v-if="ar.employee.lastname">
                    {{ ar.employee.lastname }}
                  </template>
                </strong>
              </span><br>

              <span v-if="ar.employee.department">
                <small>{{ ar.employee.department }}</small>
              </span>
            </div>

            <div>
              <template v-for="l, i in ar._links">
                <template v-if="i == 'self'">
                  <nuxt-link
                    class="button"
                    :to="`/account_requests/${ar.id}`">
                    View
                  </nuxt-link>
                </template>
              </template>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { mapFields }       from 'vuex-map-fields'
import doughnutChart       from '~/components/charts/doughnut'
import exampleBreadCrumbs from '~/components/design-system/exampleBreadCrumbs'

export default {
  components: { doughnutChart, exampleBreadCrumbs },
  data() {
    return {
      accountRequests: {
        pending:    {
          response: null,
          error:    null,
        },
        completed:  {
          response: null,
          error:    null,
        },
      },
      chartdata: {
        labels: ["Pending", "Complete"],
        datasets: [
          {
            label: "Account Request by Status",
            backgroundColor: ["rgb(255, 218, 143)", "rgb(124, 197, 126)"],
            data: [],
          }
        ]
      },
      chartoptions: {
        tooltips: {
          enabled: false,
        },
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          labels: {
            fontFamily: "'IBM Plex Sans', Helvetica, Arial, sans-serif",
            fontColor:  "#4f4f4f",
            fontSize:   16,
          },
          position: "right",
        },
      },
    }
  },
  created() {
    this.getPendingAccountRequests()
    .then((res) => {
      this.accountRequests.pending.response = res;
    })
    .catch((e)  => {
      this.accountRequests.pending.errror = e;
    });

    this.getCompletedAccountRequests()
    .then((res) => {
      this.accountRequests.completed.response = res;
    })
    .catch((e)  => {
      this.accountRequests.completed.errror = e;
    });

    
  },
  watch: {
    pendingPercent(val) {
      this.setDoughnutPercents(val);
    },
    completedPercent(val) {
      this.setDoughnutPercents(val);
    }
  },
  computed: {
    chartData() {
      return this.chartdata
    },
    chartOptions() {
      return this.chartoptions
    },
    accountRequestTotals() {
      if(this.accountRequests.pending.response &&
         this.accountRequests.completed.response) {
        
        let total = this.accountRequests.pending.response.total + this.accountRequests.completed.response.total;
        
        return total
      }
    },
    pendingPercent() {
      if(this.accountRequestTotals) {
        let percent = Math.round((this.accountRequests.pending.response.total / this.accountRequestTotals) * 100)
        return percent;
      }
    },
    completedPercent() {
      if(this.accountRequestTotals) {
        return Math.round((this.accountRequests.completed.response.total / this.accountRequestTotals) * 100)
      }
    }
  },
  methods:  {
    setDoughnutPercents(value){
      this.chartdata.datasets[0].data.push(value);
    },
    getPendingAccountRequests() {
      let pendingAccountRequestsRoute = `${process.env.backendUrl}account_requests?status=pending&format=hal`;

      return new Promise((resolve, reject) => {
        this.$axios.get(pendingAccountRequestsRoute)
        .then((res) => { resolve(res.data) })
        .catch((e)  => { reject(e) });
      })
    },
    getCompletedAccountRequests() {
      let completedAccountRequestsRoute = `${process.env.backendUrl}account_requests?status=completed&format=hal`;

      return new Promise((resolve, reject) => {
        this.$axios.get(completedAccountRequestsRoute)
        .then((res) => { resolve(res.data) })
        .catch((e)  => { reject(e) });
      })
    },
    getUserPhoto(username) {
      return this.$axios.get(`https://bloomington.in.gov/directory/people/view?username=${username}&format=json`,
      { withCredentials: false })
      .then((res) => {
        return resolve(res.data.photo)
        console.dir(res.data.photo);
      })
      .catch((e) => {
        console.dir(e);
      });
    },
  }
}
</script>

<style lang="scss" scoped>
  .dashboard {
    display: flex;
    background-color: $color-grey-lighter;
    padding: 20px;
    height: calc(100vh - 122px); // (90 + 32) = 122 = header + crumbs height
    width: calc(100% + 40px);
    transform: translateX(-20px);
  }

  .chart-container {
    position: relative;
    height: 150px;
    width: 100%;
    margin: 0;
    padding: 0;

    ::v-deep canvas {
      height: 150px !important;
      width: 100% !important;
    }
  }

  .card {
    background-color: white;
    border: 1px solid $color-grey;
    border-radius: $radius-default;
    margin: 0 0 0 20px;
    padding: 8px 20px 10px 20px;
    width: 33.3%;
    height: fit-content;
    -webkit-box-shadow: 0 16px 20px -13px rgba(42, 44, 48 , .25);
    box-shadow: 0 16px 20px -13px rgba(42, 44, 48 , .25);

    &:first-of-type {
      margin: 0;
    }

    &:hover {
      -webkit-box-shadow: 0 16px 20px -13px rgba(42, 44, 48 , .35);
      box-shadow: 0 16px 20px -13px rgba(42, 44, 48 , .35);
    }

    header {
      h1 {
        font-size: 18px;
        // letter-spacing: .25px;
        font-weight: $weight-semi-bold;
        color: $text-color;
      }
    }

    p {
      color: $text-color;

      small {
        font-size: 13px;
        font-style: italic;
      }
    }

    ul {
      li {
        // background-color: red;
        display: flex;
        align-items: center;
        border-bottom: 1px solid lighten($text-color, 50%);
        margin: 0;
        padding: 10px 0;
        

        &:nth-child(2n) {
          background: rgba(233,243,253,.3);
        }

        &:last-of-type {
          border-bottom: none;
        }

        div {
          &:nth-child(2) {
            margin-left: auto;
          }
        }

        span {
          small {
            font-size: 13px;
            font-style: italic;
          }
        }

        a, .button {
          background-color: $color-silver;
          margin: 0;
          height: auto;

          &:hover {
            background-color: darken($color-silver, 15%);
          }
        }
      }
    }
  }
</style>