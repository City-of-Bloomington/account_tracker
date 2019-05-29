import Vue          from 'vue'
import {
  mapFields }       from 'vuex-map-fields'

Vue.mixin({
  data() {
    return {
    }
  },
  computed: {
    ...mapFields([
      'consoleLog',
      'acctReqs',
    ])
  },
  methods: {
    /**
     * A Promise returning the logged in User
     *
     * @promise     getUser
     * @resolve     { Object }
     * @reject      { Error }
     * @return      { Promise <Object> } Resolves to an Object containing
     *              the current logged in User
     */
    getUser(){
      return new Promise((resolve, reject) => {
        this.$axios
        .get(`${process.env.api}${process.env.user}`)
        .then(res => resolve(res.data))
        .catch(e  => reject(e))
      })
    },
    /**
     * A Promise for Account Requests
     * by Request Status
     *
     * @promise     getAccountRequests
     * @resolve     { Object }
     * @reject      { Error }
     * @return      { Promise <Object[]> } Resolves to an Object of Arrays.
     *
     */
    getAccountRequests() {
      return new Promise((resolve, reject) => {
        let pendingReqs = new Promise((resolve, reject) => {
          this.$axios.get(`${process.env.api}${process.env.accountRequest}?limit=1000&request_status=pending`)
          .then((res) => {
            resolve(
              this.$store.dispatch('acctReqs/accountRequestsPending', res.data.results)
            )
          })
          .catch((e) => reject(`Pending Account Requests:: ${e}`))
        });

        let newReqs = new Promise((resolve, reject) => {
          this.$axios.get(`${process.env.api}${process.env.accountRequest}?limit=1000&request_status=new`)
          .then((res) => {
            resolve(
              this.$store.dispatch('acctReqs/accountRequestsNew', res.data.results)
            )
          })
          .catch((e) => reject(`New Account Requests:: ${e}`))
        });

        let deniedReqs = new Promise((resolve, reject) => {
          this.$axios.get(`${process.env.api}${process.env.accountRequest}?limit=1000&request_status=denied`)
          .then((res) => {
            resolve(
              this.$store.dispatch('acctReqs/accountRequestsDenied', res.data.results)
            )
          })
          .catch((e) => reject(`Denied Account Requests:: ${e}`))
        });

        let approvedReqs = new Promise((resolve, reject) => {
          this.$axios.get(`${process.env.api}${process.env.accountRequest}?limit=1000&request_status=denied`)
          .then((res) => {
            resolve(
              this.$store.dispatch('acctReqs/accountRequestsApproved', res.data.results)
            )
          })
          .catch((e) => reject(`Approved Account Requests:: ${e}`))
        });

        Promise.all([pendingReqs,newReqs,deniedReqs,approvedReqs])
        .then(() => {
          resolve(
            console.log(`%c getAccountRequests 👌 `, this.consoleLog.success)
          )
        })
        .catch((reject) => {
          console.log(`%c getAccountRequests 🛑 `,
                    this.consoleLog.error,
                    `\n\n ${reject} \n\n`);
        });
      })
    },
    /**
     * A Promise for an Account Request
     * by ID
     *
     * @promise     getAccountRequestByID
     * @param       { Number } id - ID of the Account Request
     * @resolve     { Object }
     * @reject      { Error }
     * @return      { Promise <Object> } Returns the Account Request Object.
     *
     */
    getAccountRequestByID(id) {
      return new Promise((resolve, reject) => {
        this.$axios
        .get(`${process.env.api}${process.env.accountRequest}${id}/`)
        .then(res => resolve(res.data))
        .catch(e =>  reject(e))
      })
    },
    /**
     * A Promise returning Services
     *
     * @promise     getServices
     * @param       { Number } limit - Number of returned results
     * @resolve     { Array }
     * @reject      { Error }
     * @return      { Promise <Object[]> } Returns an Array of Objects
     *              with each Service.
     *
     */
    getServices(limit) {
      return new Promise((resolve, reject) => {
        this.$axios
        .get(`${process.env.api}${process.env.service}?limit=${limit}`)
        .then(res => resolve(res.data.results))
        .catch(e =>  reject(e))
      })
    },
    /**
     * A Promise returning Service Requests
     * associated with an Account Request
     *
     * @promise     getServiceReqsByAcctReqId
     * @param       { Number } id    - ID of the Account Request
     * @param       { Number } limit - Number of returned results
     * @resolve     { Array }
     * @reject      { Error }
     * @return      { Promise <Object[]> } Returns an Array of Objects
     *              with each Service Request.
     *
     */
    getServiceReqsByAcctReqId(id, limit) {
      return new Promise((resolve, reject) => {
        this.$axios
        .get(`${process.env.api}${process.env.serviceReq}?account_request=${id}&limit=${limit}`)
        .then(res => resolve(res.data.results))
        .catch(e =>  reject(e))
      })
    },
    /**
     * A Promise returning Actions
     * associated with an Account Request
     *
     * @promise     getActionsByAcctReqId
     * @param       { Number } id    - ID of the Account Request
     * @param       { Number } limit - Number of returned results
     * @resolve     { Array }
     * @reject      { Error }
     * @return      { Promise <Object[]> } Returns an Array of Objects
     *              with each Service Request.
     *
     */
    getActionsByAcctReqId(id, limit) {
      return new Promise((resolve, reject) => {
        this.$axios
        .get(`${process.env.api}${process.env.action}?account=${id}&limit=${limit}`)
        .then(res => resolve(res.data.results))
        .catch(e =>  reject(e));
      })
    },
    /**
     * A Promise returning a User by ID
     *
     * @promise     getUserById
     * @param       { Number } id - ID of the Account Request
     * @resolve     { Array }
     * @reject      { Error }
     * @return      { Promise <Object> } Returns an Object
     *              of the User.
     *
     */
    getUserById(id) {
      return new Promise((resolve, reject) => {
        this.$axios
        .get(`${process.env.api}${process.env.employee}${id}/`)
        .then(res => resolve(res.data))
        .catch(e =>  reject(e));
      })
    },
    /**
     * A Promise changing the Status of a Service Request
     *
     * @promise     serviceReqStatusChange
     * @param       { Number } id     - Service Request ID
     * @param       { String } status - From `requestStatuses`
     * @resolve     { Resolve }
     * @reject      { Error }
     * @return      { Promise <Object> } Returns the updated Service Request.
     *
     */
    serviceReqStatusChange(id, status) {
      return new Promise((resolve, reject) => {
        let payload = { "request_status": status }

        this.$axios
        .patch(`${process.env.api}${process.env.serviceReq}${id}/`,payload)
        .then(res => resolve(res.data.results))
        .catch(e =>  reject(e));
      })
    },
    /**
     * A Promise logging a Service Request Action
     *
     * @promise     serviceReqAction
     * @param       { Number } authId - ID of the User making the Request
     * @param       { Object } req    - Service Req{} x Service{},
     *                                  joined where Service Req
     *                                  Service.ID = Service.ID
     * @param       { Sring } status  - From `requestStatuses`
     * @return      { Promise <Object> } The logged Service Request Action.
     *
     */
    serviceReqAction(authId, req, status) {
      return new Promise((resolve, reject) => {
        let payload = {
          "user":    authId,
          "account": req.account_request,
          "action":  `Service Request (${req.id}): ${req.name}`,
          "comment": `Changed from '${req.request_status}' to '${status}'.`
        }

        this.$axios
        .post(`${process.env.api}${process.env.action}`, payload)
        .then(res => resolve(res))
        .catch(e =>  reject(e));
      })
    },
    /**
     * A Promise for the Service Profile of a Department
     *
     * @promise     getDeptProfile
     * @param       { Number } deptID - ID of the Department
     * @resolve     { Resolve }
     * @reject      { Error }
     * @return      { Promise <Object> } The Service Profile of the Dept.
     *
     */
    getDeptProfile(deptID) {
      let errorLog = console.log(`%c getDeptProfile 🛑 `,
                                  this.consoleLog.error,
                                  `\n\n No Service Profile for this Department. \n\n`);

      return new Promise((resolve, reject) => {
        this.$axios.get(`${process.env.api}${process.env.profile}?department_id=${deptID}`)
        .then((res) => {
          console.log('yooo');
          console.log(res.data.results[0]);
          if(res.data.results.length > 0) {
            resolve(res.data.results[0]);
          } else {
            reject(errorLog)
          }
        })
        .catch(e => reject(e))
      });
    },
    /**
     * A Promise for the Service Profile
     * of a Department's Group
     *
     * @promise     getGroupProfile
     * @param       { Number } deptID  - ID of the Department
     * @param       { Number } groupID - ID of the Department's Group
     * @resolve     { Resolve }
     * @reject      { Error }
     * @return      { Promise <Object> } The Service Profile of the Dept. Group
     *
     */
    getGroupProfile(deptID, groupID) {
      let errorLog = console.log(`%c getGroupProfile 🛑 `,
                                  this.consoleLog.error,
                                  `\n\n No Service Profile for this Department Group. \n\n`);

      return new Promise((resolve, reject) => {
        this.$axios.get(`${process.env.api}${process.env.profile}?department_id=${deptID}&group_id=${groupID}`)
        .then((res) => {
          console.log(res.data.results);
          if(res.data.results.length > 0) {
            resolve(res.data.results[0]);
          } else {
            reject(errorLog)
          }
        })
        .catch(e => reject(e))
      });
    },
  }
})