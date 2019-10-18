import Vue          from 'vue'
import axios        from 'axios'
import {
mapFields }         from 'vuex-map-fields'

Vue.mixin({
  data() {
    return {}
  },
  computed: {
    ...mapFields([])
  },
  methods: {
    /**
     * A Promise for Departments
     *
     * @promise     getDepts
     * @resolve     { Object }
     * @reject      { Error }
     * @return      { Promise <Object[]> } Resolves to an Array of Objects.
     *
     */
    getDepts() {
      return new Promise((resolve, reject) => {
        axios.get(`${process.env.ttApi}${process.env.deptService}`)
        .then(res => resolve(res.data))
        .catch(e  => reject(e));
      })
    },
    /**
     * A Promise for Groups via an
     * associated Department
     *
     * @promise     getDepts
     * @param       { Number } deptID - ID of the Department
     *                         of which you want Groups from
     * @resolve     { Object }
     * @reject      { Error }
     * @return      { Promise <Object[]> } Resolves to an Array of Objects.
     *
     */
    getGroups(deptID) {
      return new Promise((resolve, reject) => {
        axios.get(`${process.env.ttApi}${process.env.groupService}?department_id=${deptID}`)
        .then(res => resolve(res.data))
        .catch(e  => reject(e));
      })
    },
    /**
     * A Promise for Jobs via an
     * associated Group
     *
     * @promise     getJobs
     * @param       { Number } groupID - ID of the Group
     *                         of which you want Jobs from
     * @resolve     { Object }
     * @reject      { Error }
     * @return      { Promise <Object[]> } Resolves to an Array of Objects.
     *
     */
    getJobs(groupID) {
      return new Promise((resolve, reject) => {
        axios.get(`${process.env.ttApi}${process.env.jobService}?group_id=${groupID}`)
        .then(res => resolve(res.data))
        .catch(e  => reject(e));
      })
    },
  }
})