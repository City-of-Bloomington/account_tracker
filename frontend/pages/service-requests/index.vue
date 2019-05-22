<template>
  <div class="inner-wrapper">
    <servicesAside
      :filterServiceIDs="serviceFilterIDs"
      @filterServiceChange="watchServiceFilters"
      :filterAcctReqIDs="acctReqFilterIDs"
      @filterAcctReqChange="watchAcctReqFilters" />

    <div class="table-wrapper">
      <div class="title-row">
        <h4><strong>Services Requests ({{displayResults.length}})</strong> you manage.</h4>

        <div class="filters">
          <fn1-badge
            v-for="s, i in requestStatuses"
            :key="i"
            :class="s">
            {{s}}
          </fn1-badge>
        </div>
      </div>

      <h2 v-if="!displayResults">Loading</h2>

      <table v-if="displayResults" class="fixed-header">
        <caption class="sr-only">All User Requests</caption>
        <thead>
          <tr>
            <th scope="col">Status</th>
            <th scope="col">Account Req.</th>
            <th scope="col">Service</th>
            <th scope="col">Updated</th>
            <th scope="col">Requested</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>

        <tbody>
          <template v-for="s, i in displayResults" >
            <tr :class="{'active': acctReqRow === i}"
                :key="i"
                @click.stop="toggleAcctReqRow(s,i,$event)">
              <td>
                <fn1-badge :class="s.request_status">
                  {{s.request_status}}
                </fn1-badge>
              </td>
              <td>{{s.ar.full_name}}</td>
              <td>{{s.sr.name}}</td>
              <td>
                <div>{{MMDYYYYDateFormat(s.updated)}}</div>
                <div>{{timeAgo(s.updated)}}</div>
              </td>
              <td>
                <div>{{MMDYYYYDateFormat(s.requested)}}</div>
                <div>{{timeAgo(s.requested)}}</div>
              </td>
              <td>
                <exampleDropdown
                  text="status"
                  navAlign="right">
                  <li v-for="rs, i in requestStatuses"
                      :class="rs"
                      @click="serviceStatusChange(s, rs)">
                      <span>{{rs}}</span>
                  </li>
                </exampleDropdown>
              </td>
            </tr>
            <tr v-if="acctReqRow === i"
                :class="{'active': acctReqRow === i}">
              <td class="acct-req-row">
                <div class="wrapper">
                  <div class="profile-image" v-if="acctReqRowAcct.cropped_image">
                    <img :src="acctReqRowAcct.cropped_image" :alt="acctReqRowAcct.first_name + ' ' + acctReqRowAcct.last_name">
                  </div>
                  <div class="avatar" v-if="!acctReqRowAcct.cropped_image">
                    {{ userInitial(acctReqRowAcct.first_name) }}{{ userInitial(acctReqRowAcct.last_name) }}
                  </div>


                  <div class="account-fields">
                    <h4>
                      <template v-if="acctReqRowAcct.first_name">
                        {{acctReqRowAcct.first_name}}
                      </template>

                      <template v-if="acctReqRowAcct.nickname">
                        ({{acctReqRowAcct.nickname}})
                      </template>

                      <template v-if="acctReqRowAcct.middle_name">
                        {{acctReqRowAcct.middle_name}}
                      </template>

                      <template v-if="acctReqRowAcct.last_name">
                        {{acctReqRowAcct.last_name}}
                      </template>

                      <template v-if="acctReqRowAcct.suffix">
                        {{acctReqRowAcct.suffix}}
                      </template>

                      <fn1-badge :class="acctReqRowAcct.request_status">
                        {{acctReqRowAcct.request_status}}
                      </fn1-badge>
                    </h4>

                    <div class="first">
                      <div v-if="acctReqRowAcct.facility">
                        <h5>Facility</h5>
                        <p>{{acctReqRowAcct.facility}}</p>
                      </div>

                      <div v-if="acctReqRowAcct.department">
                        <h5>Department</h5>
                        <p>{{acctReqRowAcct.department}}</p>
                      </div>

                      <div v-if="acctReqRowAcct.group">
                        <h5>Group</h5>
                        <p>{{acctReqRowAcct.group}}</p>
                      </div>

                      <div v-if="acctReqRowAcct.job">
                        <h5>Job</h5>
                        <p>{{acctReqRowAcct.job}}</p>
                      </div>
                    </div>

                    <div>
                      <div v-if="acctReqRowAcct.supervisor">
                        <h5>Supervisor</h5>
                        <p>{{acctReqRowAcct.supervisor}}</p>
                      </div>

                      <div v-if="acctReqRowAcct.supervisor_phone">
                        <h5>Supervisor Phone</h5>
                        <p>{{acctReqRowAcct.supervisor_phone}}</p>
                      </div>

                      <div v-if="acctReqRowAcct.employee_phone">
                        <h5>Employee Phone</h5>
                        <p>{{acctReqRowAcct.employee_phone}}</p>
                      </div>

                      <div v-if="acctReqRowAcct.clock_entry_only">
                        <h5>Clock Entry Only</h5>
                        <p>{{acctReqRowAcct.clock_entry_only}}</p>
                      </div>

                      <div v-if="acctReqRowAcct.employee_status">
                        <h5>Employee Status</h5>
                        <p>{{acctReqRowAcct.employee_status}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>

          </template>

        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { createHelpers } from 'vuex-map-fields';

const { mapFields } = createHelpers({
  getterType: `getField`,
  mutationType: `updateField`,
});

import exampleDropdown  from '~/components/exampleDropdown'
import servicesAside    from '~/components/service-requests/servicesAside'

export default {
  layout:           'service-requests',
  components: {
    exampleDropdown,
    servicesAside,
  },
  mounted() {
    this.$nextTick(() => {
      this.loadData();
      this.displayResults;
    });
  },
  data() {
    return {
      mgrID:            null,
      serviceFilterIDs: [],
      acctReqFilterIDs: [],
      acctReqRow:       null,
      acctReqRowAcct:   null,
      show:             false
    }
  },
  methods: {
    getUserId() {
      return new Promise((resolve) => {
        resolve(this.authUser.id)
      })
    },
    watchServiceFilters(payload) {
      console.log(`%c watchServiceFilters ${payload} `, this.consoleLog.info);
      this.serviceFilterIDs = payload;
    },
    watchAcctReqFilters(payload) {
      console.log(`%c watchAcctReqFilters ${payload} `, this.consoleLog.info);
      this.acctReqFilterIDs = payload;
    },
    loadData() {
      this.getServices()
      .then((resolve) => {
        this.$store.dispatch('serviceReqs/setServices', resolve);
        console.log(`%c 1. getServices 👌 `, this.consoleLog.success);

        this.getServiceRequests()
        .then((resolve) => {
          this.$store.dispatch('serviceReqs/setRequests', resolve);
          console.log(`%c 2. getServiceRequests 👌 `, this.consoleLog.success);

          this.getManagerServiceSet()
          .then((resolve) => {
            console.log(`%c 3. getManagerServiceSet 👌 `, this.consoleLog.success);
            this.$store.dispatch('serviceReqs/setMgrProfileIDs', resolve);

            this.mgrServices()
            .then((resolve) => {
              this.$store.dispatch('serviceReqs/setMgrFullProfiles', resolve);
              console.log(`%c 4. mgrServices 👌 `, this.consoleLog.success);

              this.setMgrServiceReqs()
              .then((resolve) => {
                this.$store.dispatch('serviceReqs/setMgrServiceReqs', resolve);
                console.log(`%c 5. setMgrServiceReqs 👌 `, this.consoleLog.success);

                this.filterActives()
                .then((resolve) => {
                  this.managerAcctReqXServiceReq();

                  console.log(`%c 6. filterActives 👌 `, this.consoleLog.success);
                }, (reject) => {
                  console.log(`%c 6. filterActives 🛑 `, this.consoleLog.error);
                });
              }, (reject) => {
                console.log(`%c 5. setMgrServiceReqs 🛑 `, this.consoleLog.error);
              })
            }, (reject) => {
              console.log(`%c 4. mgrServices 🛑 `,
                        this.consoleLog.error,
                        `\n\n ${reject} \n\n`);
            });
          }, (reject) => {
            console.log(`%c 3. getManagerServiceSet 🛑 `,
                        this.consoleLog.error,
                        `\n\n ${reject} \n\n`);
          });
        }, (reject) => {
          console.log(`%c 2. getServiceRequests 🛑 `,
                        this.consoleLog.error,
                        `\n\n ${reject} \n\n`);
        });
      }, (reject) => {
        console.log(`%c 1. getServices 🛑 `,
                        this.consoleLog.error,
                        `\n\n ${reject} \n\n`);
      });
    },
    getManagerServiceSet() {
      return new Promise((resolve,reject) => {
        this.$axios
        .get(`${process.env.api}${process.env.employee}${this.authUser.id}/`)
        .then((res) => {
          resolve(res.data.service_set);
        })
        .catch((e) => {
          reject(e);
        });
      });
    },
    getServices() {
      return new Promise((resolve,reject) => {
        this.$axios
        .get(`${process.env.api}${process.env.service}?limit=5000`)
        .then((res) => {
          resolve(res.data.results);
        })
        .catch((e) => {
          reject(e);
        });
      });
    },
    getServiceRequests() {
      return new Promise((resolve, reject) => {
        this.$axios
        .get(`${process.env.api}${process.env.serviceReq}?limit=5000`)
        .then((res) => {
          resolve(res.data.results);
        })
        .catch((e) => {
          reject(e);
        });
      });
    },
    mgrServices() {
      return new Promise((resolve) => {
        resolve(this.services.filter((item) => {
            return this.mgrProfileIDs.indexOf(item.id) >= 0;
          })
        )
      });
    },
    setMgrServiceReqs() {
      return new Promise((resolve) => {
        resolve(this.requests.filter((item) => {
          return this.mgrProfileIDs.indexOf(item.service) >= 0;
          })
        )
      });
    },
    serviceStatusChange(acctReq, status) {
      let payload = {
        "request_status": status,
      }

      this.$axios
      .patch(`${process.env.api}${process.env.serviceReq}${acctReq.id}/`,payload)
      .then((res) => {
        console.log(`serviceStatusChange() :: `, res.data);

        this.$axios
        .post(`${process.env.api}${process.env.action}`,{
          "user":    this.authUser.id,
          "account": acctReq.ar.id,
          "action":  `Service Request (${acctReq.id}): ${acctReq.sr.name}`,
          "comment": `Changed from '${acctReq.request_status}' to '${status}'.`
        })
        .then(response => {
          console.log(`ACTION SR serviceStatusChange :: `, response);
          this.loadData();
        })
        .catch(e => {
          console.log(`ACTION SR serviceStatusChange error :: `, e)
        });

      })
      .catch((error) => {
        console.log(`serviceStatusChange() error:: `, error);
      })
    },
    toggleAcctReqRow(service, i, e) {
      let clicked = e.srcElement.nodeName;
      if(clicked === 'TD') {
        this.acctReqRowAcct = null;
        let serviceIdx      = service.account_request,
        sameRow             = (this.acctReqRow === i);
        sameRow ? this.acctReqRow = null : this.acctReqRow = i;

        let copy = [...this.acctReqsByServiceReq];
        let copyResults = copy.filter((acctReq) => {
          if(acctReq.id === serviceIdx)
            return acctReq
        })

        this.acctReqRowAcct = copyResults[0];
      }
    },
    filterActives() {
      return new Promise((resolve, reject) => {
        let activeServices = [],
        activeAcctReqs     = [];

        this.mgrServiceReqs.forEach((item) => {
          resolve(
            activeServices.push(item.service),
            activeAcctReqs.push(item.account_request)
          )
        });

        let uniqActiveServices = [...new Set(activeServices)],
        uniqActiveAcctReqs     = [...new Set(activeAcctReqs)];

        this.$store.dispatch('serviceReqs/setActiveServiceIDs', uniqActiveServices);

        this.$store.dispatch('serviceReqs/setActiveAcctReqIDs', uniqActiveAcctReqs);

        reject('The filterActives forEach failed ...');
      })
    },
    managerAcctReqXServiceReq() {
      let results           = [],
      acctReqsXService      = this.activeAcctReqIDs,
      uAcctReqsXService     = [...new Set(acctReqsXService)];

      let requests = uAcctReqsXService.map((item) => {
        return new Promise((resolve, reject) => {
          this.$axios
          .get(`${process.env.api}${process.env.accountRequest}?id=${item}`)
          .then((res) => {
            resolve(results.push(res.data.results[0]));
          })
          .catch((e) => {
            reject(e);
          });
        });
      });

      Promise.all(requests).then(() =>
       this.$store.dispatch('serviceReqs/setAcctReqsByServiceReq', results),
      ).then(() => this.masterManagerServiceReqs());
    },
    masterManagerServiceReqs() {
      let masterServices = [];
      let newNew = this.mgrServiceReqs.filter((item) => {
        this.acctReqsByServiceReq.filter((ar) => {
          if(ar.id === item.account_request) {
            this.mgrFullProfiles.filter((s) => {
              if(s.id === item.service) {
                masterServices.push({...item, sr: {...s}, ar: {...ar}})
              }
            })
          }
        })
      });
      return this.$store.dispatch('serviceReqs/setMaster', masterServices)
    },
  },
  computed: {
    ...mapFields([
      'auth.authUser',
      'consoleLog',
      'requestStatuses',
      'serviceReqs.master',
      'serviceReqs.requests',
      'serviceReqs.services',
      'serviceReqs.mgrProfileIDs',
      'serviceReqs.mgrFullProfiles',
      'serviceReqs.mgrServiceReqs',
      'serviceReqs.activeServiceIDs',
      'serviceReqs.activeAcctReqIDs',
      'serviceReqs.acctReqsByServiceReq'
    ]),
    displayResults() {
      let hasServiceFilters = this.serviceFilterIDs.length != 0,
      hasAcctReqFilters     = this.acctReqFilterIDs.length != 0,
      hasBothFilters        = hasServiceFilters && hasAcctReqFilters;

      let masterCopy = [...this.master];

      if(hasBothFilters){
        return masterCopy
        .filter((item) => {
          return this.serviceFilterIDs.indexOf(item.service) >= 0 &&
                 this.acctReqFilterIDs.indexOf(item.account_request) >= 0;
        })
        .sort((a, b) => new Date(b.updated) - new Date(a.updated))
      } else if(hasServiceFilters) {
        return masterCopy
        .filter((item) => {
          return this.serviceFilterIDs.indexOf(item.service) >= 0;
        })
        .sort((a, b) => new Date(b.updated) - new Date(a.updated))
      } else if(hasAcctReqFilters) {
        return masterCopy
        .filter((item) => {
          return this.acctReqFilterIDs.indexOf(item.account_request) >= 0;
        })
        .sort((a, b) => new Date(b.updated) - new Date(a.updated))
      } else {
        return masterCopy
        .sort((a, b) => new Date(b.updated) - new Date(a.updated))
      }
    },
  }
}
</script>

<style lang="scss" scoped>

  .inner-wrapper{
    display: flex;
    position: relative;
  }

  .navigation-dropdown {
    /deep/ ul {
      width: auto !important;

      li {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
        margin: 0;
        padding: 5px 10px;
        color: $text-color;
        font-weight: $weight-semi-bold;
        text-align: left;
        border: none;
        border-bottom: 1px solid lighten($text-color, 60%);

        span {
          margin: 0 0 0 25px;
        }

        &:hover {
          border-radius: 0;
          border-top: none;
          border-right: none;
          border-left: none;
        }

        &:before {
          position: absolute;
          content: '';
          width: 15px;
          height: 15px;
          border-radius: 50%;
        }

        &.new {
          &:before {
            background-color: $color-ucla-gold-dark;
          }
        }

        &.pending {
          &:before {
            background-color: $color-blue;
          }
        }

        &.approved {
          &:before {
            background-color: $color-green-light;
          }
        }

        &.active {
          border-radius: 0;

          &:before {
            background-color: $color-green;
          }
        }

        &.denied {
          &:before {
            background-color: $color-vermilion-darker;
          }
        }

        &.inactive {
          &:before {
            background-color: $text-color;
          }
        }
      }
    }
  }

  .table-wrapper {
    display: table;
    position: relative;
    width: calc(100% - 300px);
    margin-left: auto;

    &:after {
      position: absolute;
      content: '';
      pointer-events: none;
      right: 0;
      bottom: 0;
      left: 0;
      height: 30px;
      width: 100%;
      background: linear-gradient(rgba(255, 255, 255, 0) 0%, white 100%);
    }

    h1 {
      color: $text-color;
      font-size: $size-l;
      line-height: $size-l;
      margin: 0 0 $space-m 0;
      padding: 0 0 $space-s 0;
      border-bottom: 1px solid lighten($text-color, 50%);
    }

    .title-row {
      position: fixed;
      width: calc(100% - 340px);
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 0 20px 0;
      min-height: 25px;

      h4 {
        color: $text-color;
        font-size: 18px;
        line-height: 18px;
      }

      .filters {
        margin-left: auto;
        display: flex;

        .badge {
          margin: 0 10px 0 0;

          &:last-of-type {
            margin: 0;
          }
        }
      }

      .field-group {
        display: flex;
        width: 350px;
        min-width: 350px;
        max-width: 350px;
        margin: 0 0 0 20px;

        &:hover {
          input {
            border-color: lighten($text-color, 30%);
          }
        }

        input {
          border-radius: $radius-default;

          &:focus {
            border-color: lighten($text-color, 30%);
          }
        }
      }
    }

    table {
      margin: 60px 0 0 0;
      color: $text-color;

      thead tr th,
      tbody tr td {
        &:nth-child(2) {
          width: 300px;
        }

        &:nth-of-type(3) {
          min-width: 350px;
          width: 350px;
        }

        &:last-of-type {
          text-align: right;
        }
      }

      thead {
        background-color: transparent;
        border-bottom: 1px solid #ddd;
      }

      tbody {
        height: calc(100vh - 300px);

        tr {
          cursor: pointer;

          &.active {
            background-color: lighten($color-grey, 7%);
          }

          td {
            &.acct-req-row {
              display: block;
              width: 100%;
              padding: 8px;
              border-top: none;

              .wrapper {
                display: flex;
                flex-wrap: wrap;
                width: 100%;

                .avatar {
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  margin: 0;
                  background-color: lighten($color-blue, 45%);
                  width: 75px;
                  height: 75px;
                  border-radius: 50%;
                }

                .profile-image {
                  margin: 0;
                  width: 75px;
                  height: 75px;

                  img {
                    left: 50%;
                    transform: translate(-50%, -50%);
                  }
                }

                .account-fields {
                  background-color: lighten($color-grey, 15%);
                  border-radius: $radius-default;
                  border: 1px solid #ddd;
                  padding: 10px;
                  text-align: left;
                  width: calc(100% - 105px);
                  margin-left: auto;

                  h4 {
                    margin: 0 0 15px 0;
                    padding: 0 0 10px 0;
                    display: flex;
                    align-items: center;
                    border-bottom: 1px solid #ddd;

                    span {
                      margin: 0 0 0 10px;
                    }
                  }

                  h5 {
                    color: $text-color;
                    font-size: 18px;
                    margin: 0 0 2px 0;
                    font-weight: 600;
                  }

                  .field-group {
                    width: 100%;
                  }

                  div {
                    display: flex;
                    flex-wrap: wrap;
                    flex-basis: 100%;

                    &.first {
                      margin: 0 0 10px 0;
                    }

                    > div {
                      flex-basis: 25%;
                    }
                  }
                }
              }
            }

            p {
              font-size: 14px;
              color: lighten($text-color, 25%);
            }

            div {
              display: block;
              width: 100%;

              &:nth-child(2) {
                font-size: 14px;
                color: lighten($text-color, 25%);
              }
            }

            .navigation-dropdown {
              margin-left: auto;

              button {
                background-color: darken($color-silver, 10%);
                padding: 0 10px;
              }
            }
          }
        }
      }
    }
  }
</style>