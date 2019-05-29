<template>
  <div>
    <div class="search">
      <div class="wrapper">
        <fn1-input v-model="serviceSearch"
                 label="Service Search"
                 placeholder="Search by Name"
                 name="acct-req-search"
                 id="acct-req-search" />
      </div>
    </div>

    <div class="results-wrapper">
      <div class="results-header">
        <h4>Total Services: <strong>{{allServices.length}}</strong></h4>

        <exampleModal ref="addServiceModal"
                      class="addServiceModal"
                      title="Add - Service"
                      launchButtonText="+ add service">

              <template slot="body">
                <form>
                  <div class="left">
                    <exampleSelect v-model="formDeployment"
                                 label="Deployment"
                                 name="deployment"
                                 id="deployment"
                                 :options="serviceDeploymentOptions" />

                    <div class="form-group inline">
                      <fieldset>
                        <legend>Active?</legend>
                        <div v-for="r, i in tfRadios">
                          <input type="radio"
                                 name="active-radio"
                                 id="active-radio"
                                 :value="r.value"
                                 v-model="formActive">
                          <label for="active-radio">{{r.text}}</label>
                        </div>
                      </fieldset>
                    </div>

                    <div class="form-group inline">
                      <fieldset>
                        <legend>Standard Build?</legend>
                        <div v-for="r, i in tfRadios">
                          <input type="radio"
                                 name="standard-build"
                                 id="standard-build"
                                 :value="r.value"
                                 v-model="formBuild">
                          <label for="standard-build">{{r.text}}</label>
                        </div>
                      </fieldset>
                    </div>

                    <div class="form-group inline">
                      <fieldset>
                        <legend>Public?</legend>
                        <div v-for="r, i in tfRadios">
                          <input type="radio"
                                 name="public"
                                 id="public"
                                 :value="r.value"
                                 v-model="formPublic">
                          <label for="public">{{r.text}}</label>
                        </div>
                      </fieldset>
                    </div>

                    <div class="form-group inline">
                      <fieldset>
                        <legend>Internal?</legend>
                        <div v-for="r, i in tfRadios">
                          <input type="radio"
                                 name="internal"
                                 id="internal"
                                 :value="r.value"
                                 v-model="formInternal">
                          <label for="internal">{{r.text}}</label>
                        </div>
                      </fieldset>
                    </div>
                  </div>

                  <div class="right">
                    <fn1-input v-model="formName"
                               label="Name"
                               placeholder="Name"
                               name="name"
                               id="name" />

                    <fn1-input v-model="formUrl"
                               label="URL"
                               placeholder="URL"
                               name="formUrl"
                               id="formUrl" />

                    <div class="row">
                      <fn1-input v-model="formDeveloper"
                               label="Developer"
                               placeholder="Developer"
                               name="developer"
                               id="developer" />

                      <fn1-input v-model="formVersion"
                               label="Version"
                               placeholder="Version"
                               name="version"
                               id="version" />
                    </div>

                    <div class="field-group">
                      <label for="description">Description</label>
                      <textarea v-model="formDescription"
                                type="textarea"
                                id="description"
                                placeholder="An optional Service description."></textarea>
                    </div>
                  </div>
                </form>
              </template>

              <fn1-button slot="footer"
                          class="confirm"
                          title="Confirm - Remove Service"
                          @click.native="addService()">
                Confirm
              </fn1-button>

              <fn1-button slot="footer"
                          class="cancel"
                          title="Cancel - Remove Service"
                          @click.native="closeModal('addServiceModal')">
                Cancel
              </fn1-button>
            </exampleModal>

        <!-- <exampleModal ref="addServiceModal"
                      title="Add - Service"
                      launchButtonText="+ add service">

          <template slot="body">
            <form>
              <div class="row">
                <exampleSelect v-model="formDeployment"
                             label="Deployment"
                             name="deployment"
                             id="deployment"
                             :options="serviceDeploymentOptions" />

                <div class="form-group inline">
                  <fieldset>
                    <legend>Active?</legend>
                    <div v-for="r, i in tfRadios">
                      <input type="radio"
                             name="active-radio"
                             id="active-radio"
                             :value="r.value"
                             v-model="formActive">
                      <label for="active-radio">{{r.text}}</label>
                    </div>
                  </fieldset>
                </div>

                <div class="form-group inline">
                  <fieldset>
                    <legend>Standard Build?</legend>
                    <div v-for="r, i in tfRadios">
                      <input type="radio"
                             name="standard-build"
                             id="standard-build"
                             :value="r.value"
                             v-model="formBuild">
                      <label for="standard-build">{{r.text}}</label>
                    </div>
                  </fieldset>
                </div>

                <div class="form-group inline">
                  <fieldset>
                    <legend>Public?</legend>
                    <div v-for="r, i in tfRadios">
                      <input type="radio"
                             name="public"
                             id="public"
                             :value="r.value"
                             v-model="formPublic">
                      <label for="public">{{r.text}}</label>
                    </div>
                  </fieldset>
                </div>

                <div class="form-group inline">
                  <fieldset>
                    <legend>Internal?</legend>
                    <div v-for="r, i in tfRadios">
                      <input type="radio"
                             name="internal"
                             id="internal"
                             :value="r.value"
                             v-model="formInternal">
                      <label for="internal">{{r.text}}</label>
                    </div>
                  </fieldset>
                </div>
              </div>

              <fn1-input v-model="formName"
                         label="Name"
                         placeholder="Name"
                         name="name"
                         id="name" />

              <fn1-input v-model="formUrl"
                         label="URL"
                         placeholder="URL"
                         name="formUrl"
                         id="formUrl" />

              <div class="row">
                <fn1-input v-model="formDeveloper"
                         label="Developer"
                         placeholder="Developer"
                         name="developer"
                         id="developer" />

                <fn1-input v-model="formVersion"
                         label="Version"
                         placeholder="Version"
                         name="version"
                         id="version" />
              </div>

              <div class="row">
                <fn1-input v-model="formPrimaryPOC"
                         label="Primary POC"
                         placeholder="Primary Point of Contact"
                         name="primary-poc"
                         id="primary-poc" />

                <fn1-input v-model="formSecondaryPOC"
                           label="Secondary POC"
                           placeholder="Secondary Point of Contact"
                           name="secondary-poc"
                           id="secondary-poc" />
              </div>

              <div class="field-group">
                <label for="description">Description</label>
                <textarea v-model="formDescription"
                          type="textarea"
                          id="description"
                          placeholder="An optional Service description."></textarea>
              </div>
            </form>
          </template>

          <fn1-button slot="footer"
                      class="confirm"
                      title="Confirm - Remove Service"
                      @click.native="addService()">
            Confirm
          </fn1-button>

          <fn1-button slot="footer"
                      class="cancel"
                      title="Cancel - Remove Service"
                      @click.native="closeModal('addServiceModal')">
            Cancel
          </fn1-button>
        </exampleModal> -->
      </div>

      <div class="sidebar">
        <p>
          <strong>Updated:</strong>
          <small>{{MMDYYYYDateFormat(lastUpdated)}}</small>
          <small>{{timeAgo(lastUpdated)}}</small>
        </p>

        <div class="status-legend">
          <fn1-badge v-for="s, i in filterStatusLegend"
                     :key="i"
                     :class="s">
            <template v-if="s === 'active'">
              {{s}} <small>&nbsp;{{activeServicesCount}}</small>
            </template>
            <template v-if="s === 'inactive'">
              {{s}} <small>&nbsp;{{inactiveServicesCount}}</small>
            </template>
          </fn1-badge>
        </div>
      </div>

      <div class="results">
        <div v-if="loading" class="loading">
          <h4>Loading Services . . .</h4>
        </div>

        <div v-if="allServices" v-for="s, i in filteredServices"
             class="result"
             :class="{'active': s.active, 'inactive': !s.active}">

          <div class="col">
            <p class="label">{{s.name}}</p>
          </div>

          <div class="col">
            <p class="label">Last Update</p>
            <p v-if="s.updated">{{MMDYYYYDateFormat(s.updated)}}</p>
            <p v-if="s.updated">{{timeAgo(s.updated)}}</p>
          </div>

          <div class="col">
            <p class="label">
              Manager<template v-if="s.managers.length > 1">s</template>
            </p>
            <template v-if="s.managers.length === 0">
              <p>--</p>
            </template>
            <template v-for="sm, i in s.managers">
              <p>{{sm.first_name}} {{sm.last_name}}</p>
            </template>
          </div>

          <div class="col actions">
            <exampleModal ref="editServiceModal"
                          class="editServiceModal"
                          :title="`Edit - Service: ${s.name}`"
                          @displayModal="editModalHandler(s, i)"
                          launchButtonText="edit">

              <template slot="body" ref="editServiceFormBody">
                <template v-if="addingServiceManager">
                  <div class="search-wrapper">
                    <fn1-input v-model="serviceManagerSearch"
                               label="Search Service Managers"
                               autocomplete="off"
                               placeholder="Search Service Managers"
                               name="service-manager-search"
                               id="service-manager-search" />

                      <ul class="search-results" v-if="serviceManagerSearch">
                        <li v-for="m, i in filteredEmployees"
                            :key="i">
                          <div class="edit-wrapper">
                            <div class="actions">
                              <fn1-button
                                @click.native="addServiceManager(s, m)">
                                + add
                              </fn1-button>
                            </div>
                          </div>
                          <strong>{{m.id}}:</strong>&nbsp;{{m.first_name}}
                        </li>
                      </ul>
                  </div>
                </template>

                <form v-show="!addingServiceManager">
                  <div class="left">
                    <exampleSelect v-model="formEditDeployment"
                                 label="Deployment"
                                 name="deployment"
                                 id="deployment"
                                 :options="serviceDeploymentOptions" />

                    <div class="form-group inline">
                      <fieldset>
                        <legend>Active?</legend>
                        <div v-for="r, i in tfRadios">
                          <input type="radio"
                                 name="active-radio"
                                 id="active-radio"
                                 :value="r.value"
                                 v-model="formEditActive">
                          <label for="active-radio">{{r.text}}</label>
                        </div>
                      </fieldset>
                    </div>

                    <div class="form-group inline">
                      <fieldset>
                        <legend>Standard Build?</legend>
                        <div v-for="r, i in tfRadios">
                          <input type="radio"
                                 name="standard-build"
                                 id="standard-build"
                                 :value="r.value"
                                 v-model="formEditBuild">
                          <label for="standard-build">{{r.text}}</label>
                        </div>
                      </fieldset>
                    </div>

                    <div class="form-group inline">
                      <fieldset>
                        <legend>Public?</legend>
                        <div v-for="r, i in tfRadios">
                          <input type="radio"
                                 name="public"
                                 id="public"
                                 :value="r.value"
                                 v-model="formEditPublic">
                          <label for="public">{{r.text}}</label>
                        </div>
                      </fieldset>
                    </div>

                    <div class="form-group inline">
                      <fieldset>
                        <legend>Internal?</legend>
                        <div v-for="r, i in tfRadios">
                          <input type="radio"
                                 name="internal"
                                 id="internal"
                                 :value="r.value"
                                 v-model="formEditInternal">
                          <label for="internal">{{r.text}}</label>
                        </div>
                      </fieldset>
                    </div>

                    <ul>
                      <li>
                        <span class="label">Managers</span>

                        <fn1-button @click.native="showAddServiceManager(s,i)">
                          Add
                        </fn1-button>
                      </li>

                      <template v-for="m, i in s.managers">
                        <li>
                          {{m.first_name}} {{m.last_name}}

                          <exampleModal ref="removeServiceManagerModal"
                                        class="removeServiceManagerModal"
                                        title="Remove - Service Manager"
                                        launchButtonText="&#10005;">

                            <p slot="body">Remove <strong>{{m.id}}: {{m.first_name}} {{m.last_name}}</strong> as a manager from <strong>{{s.name}}</strong>?</p>

                            <fn1-button slot="footer"
                                        class="confirm"
                                        title="Confirm - Remove Service"
                                        @click.native="removeServiceManager(m, s, i)">
                              Confirm
                            </fn1-button>

                            <fn1-button slot="footer"
                                        class="cancel"
                                        title="Cancel - Remove Service"
                                        @click.native="closeModal('removeServiceManagerModal', i)">
                              Cancel
                            </fn1-button>
                          </exampleModal>
                        </li>
                      </template>
                    </ul>
                  </div>

                  <div class="right">
                    <fn1-input v-model="formEditName"
                               label="Name"
                               placeholder="Name"
                               name="name"
                               id="name" />

                    <fn1-input v-model="formEditUrl"
                               label="URL"
                               placeholder="URL"
                               name="formUrl"
                               id="formUrl" />

                    <div class="row">
                      <fn1-input v-model="formEditDeveloper"
                               label="Developer"
                               placeholder="Developer"
                               name="developer"
                               id="developer" />

                      <fn1-input v-model="formEditVersion"
                               label="Version"
                               placeholder="Version"
                               name="version"
                               id="version" />
                    </div>

                    <div class="field-group">
                      <label for="description">Description</label>
                      <textarea v-model="formEditDescription"
                                type="textarea"
                                id="description"
                                placeholder="An optional Service description."></textarea>
                    </div>
                  </div>
                </form>
              </template>

              <template v-if="addingServiceManager">
                <fn1-button slot="footer"
                            class="cancel"
                            title="Cancel - Add Service Manager"
                            @click.native="cancelAddServiceManager()">
                  Cancel - Add Service Manager
                </fn1-button>
              </template>

              <template v-if="!addingServiceManager">
                <fn1-button slot="footer"
                            class="confirm"
                            title="Confirm - Edit Service"
                            @click.native="editService(i)">
                  Confirm
                </fn1-button>

                <fn1-button slot="footer"
                            class="cancel"
                            title="Cancel - Edit Service"
                            @click.native="closeModal('editServiceModal', i)">
                  Cancel
                </fn1-button>
              </template>
              <p slot="footer">Last Updated: <small>{{timeAgo(s.updated)}}</small></p>
            </exampleModal>

            <exampleModal ref="removeServiceModal"
                          title="Remove - Service"
                          launchButtonText="&#10005;">

              <p slot="body">Remove <strong>{{s.name}}</strong>?</p>

              <fn1-button slot="footer"
                          class="confirm"
                          title="Confirm - Remove Service"
                          @click.native="removeService(s, i)">
                Confirm
              </fn1-button>

              <fn1-button slot="footer"
                          class="cancel"
                          title="Cancel - Remove Service"
                          @click.native="closeModal('removeServiceModal', i)">
                Cancel
              </fn1-button>
            </exampleModal>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapFields } from 'vuex-map-fields'

import exampleModal   from '~/components/exampleModal'
import exampleSelect  from '~/components/exampleSelect'

export default {
  middleware:       ['isAdminOrSupportLevel'],
  layout:           'services',
  components: {
    exampleModal,
    exampleSelect
  },
  mounted() {
    this.loading = true;
    this.loadServices();
  },
  data() {
    return {
      loading:          false,
      allServices:      [],
      serviceManagers:  [],
      addingServiceManager: false,
      serviceManagerSearch: '',
      employees:        [],
      editServiceBodyHeight: null,
      serviceSearch:    '',
      serviceDeploymentOptions: [
        {text: 'Desktop', value: 'desktop'},
        {text: 'Mobile',  value: 'mobile'},
        {text: 'Cloud',   value: 'cloud'},
      ],
      tfRadios:     [
        { text: 'True',  value: true },
        { text: 'False', value: false }
      ],
      // Form - New
      formActive:       null,
      formName:         null,
      formPublic:       null,
      formDescription:  null,
      formUrl:          null,
      formDeployment:   null,
      formBuild:        null,
      formInternal:     null,
      formVersion:      null,
      formDeveloper:    null,

      // Form - Edit
      formEditID:           null,
      formEditName:         null,
      formEditManagers:     null,
      formEditDescription:  null,
      formEditUrl:          null,
      formEditActive:       null,
      formEditDeployment:   null,
      formEditBuild:        null,
      formEditPublic:       null,
      formEditInternal:     null,
      formEditVersion:      null,
      formEditDeveloper:    null,
    }
  },
  computed: {
    ...mapFields([
      'apiLimit',
      'auth.authUser',
      'consoleLog',
      'requestStatuses',
    ]),
    editServiceFormBody() {
      this.editServiceBodyHeight = this.$refs.editServiceFormBody.clientHeight;
    },
    filterStatusLegend() {
      return this.requestStatuses
      .filter((q) => q !== 'new')
      .filter((q) => q !== 'pending')
      .filter((q) => q !== 'approved')
      .filter((q) => q !== 'denied');
    },
    activeServicesCount() {
      return this.allServices.filter((s) => s.active == true).length
    },
    inactiveServicesCount() {
      return this.allServices.filter((s) => s.active == false).length
    },
    lastUpdated() {
      if(this.allServices.length) {
        let copy = [...this.allServices];
        copy.sort((a,b) =>
          new Date(b.updated) - new Date(a.updated)
        );
        return copy[0].updated
      }
    },
    filteredServices() {
      return this.allServices
      .filter(service => {
        let serviceName   = service.name.toLowerCase();

        return serviceName.includes(this.serviceSearch.toLowerCase())
      })
      .sort((a, b) => {
        let nameA = a.name.toUpperCase(),
        nameB     = b.name.toUpperCase();
        return (nameA < nameB) ? -1 : (nameA > nameB) ? 1 : 0;
      });
    },
    servicesWithManager() {
      let servicesXManager = [];

      this.allServices.forEach((s, i) => {
        this.serviceManagers.forEach((m, i) => {
          if(s.id === m.service) {
            delete m.id
            delete m.created
            masterServices.push({...s,...m})
          }
        })
      });
      return servicesXManager;
    },
    filteredEmployees() {
      return this.employees
      .filter(e => {
        let firstName  = e.first_name.toLowerCase(),
        lastName       = e.last_name.toLowerCase(),
        userName       = e.username.toLowerCase();

        return firstName.includes(this.serviceManagerSearch.toLowerCase()) ||
               lastName.includes(this.serviceManagerSearch.toLowerCase()) ||
               userName.includes(this.serviceManagerSearch.toLowerCase())
      })
    },
  },
  methods: {
    showAddServiceManager() {
      this.addingServiceManager = true;
    },
    addServiceManager(service,manager) {
      let serviceID = service.id,
      managerID     = manager.id;

      this.$axios
      .post(`${process.env.api}${process.env.service}${serviceID }/add_manager/${managerID}/`)
      .then((res) => {
        this.addingServiceManager = false;
        this.loadServices();
        console.log(`%c addServiceManager 👌 `, this.consoleLog.success);
      })
      .catch((e) => {
        console.log(`%c addServiceManager 🛑 `, this.errLogStyle);
      });
    },
    cancelAddServiceManager() {
      this.addingServiceManager = false;
      this.serviceManagerSearch = '';
    },
    removeServiceManager(manager, service, i) {
      let serviceID = service.id,
      managerID     = manager.id;

      this.$axios
      .post(`${process.env.api}${process.env.service}${serviceID }/remove_manager/${managerID}/`)
      .then((res) => {
        this.loadServices();
        console.log(res);
        console.log(`%c removeServiceManager 👌 `, this.consoleLog.success);
      })
      .catch((e) => {
        console.log(e);
        console.log(`%c removeServiceManager 🛑 `, this.errLogStyle);
      });
    },
    closeModal(modalRef, i) {
      if(modalRef === 'editServiceModal')
        this.$refs.editServiceModal[i].showModal = false;
      if(modalRef === 'removeServiceModal')
        this.$refs.removeServiceModal[i].showModal = false;
      if(modalRef === 'addServiceModal')
        this.$refs.addServiceModal.showModal = false;
      if(modalRef === 'removeServiceManagerModal')
        this.$refs.removeServiceManagerModal[i].showModal = false;
    },
    addService() {
      let fD = new FormData();
      fD.append(`active`,         this.formActive);
      fD.append(`name`,           this.formName);
      fD.append(`public`,         this.formPublic);
      fD.append(`description`,    this.formDescription);
      fD.append(`url`,            this.formUrl);
      fD.append(`deployment`,     this.formDeployment);
      fD.append(`standard_build`, this.formBuild);
      fD.append(`internal`,       this.formInternal);
      fD.append(`version`,        this.formVersion);
      fD.append(`developer`,      this.formDeveloper);

      this.$axios
      .post(`${process.env.api}${process.env.service}`,fD)
      .then((res) => {
        this.$refs.addServiceModal.showModal = false;
        this.loadServices();
        console.log(`%c addService 👌 `, this.consoleLog.success);
      })
      .catch((e) => {
        console.log(e);
        console.log(`%c addService 🛑 `, this.errLogStyle);
      });
    },
    removeService(s, i) {
      this.$axios
      .delete(`${process.env.api}${process.env.service}${s.id}/`)
      .then((res) => {
        this.$refs.removeServiceModal[i].showModal = false;
        this.loadServices();
        console.log(`%c removeService 👌 `, this.consoleLog.success);
      })
      .catch((e) => {
        console.log(`%c removeService 🛑 `,
                    this.consoleLog.error,
                    `\n\n ${e} \n\n`);
      });
    },
    loadServices(){
      this.getServices(this.apiLimit)
      .then((resolve) => {
        this.allServices = resolve;
        this.getEmployees();
        this.loading = false;
        console.log(`%c getServices (loadServices) 👌 `,
                    this.consoleLog.success);
      })
      .catch((reject) => {
        console.log(`%c getServices (loadServices) 🛑 `,
                    this.consoleLog.error,
                    `\n\n ${reject} \n\n`);
      });
    },
    editModalHandler(s, i) {
      let copy = s;
      this.formEditID           = copy.id,
      this.formEditName         = copy.name,
      this.formEditManagers     = copy.managers,
      this.formEditDescription  = copy.description,
      this.formEditUrl          = copy.url,
      this.formEditActive       = copy.active,
      this.formEditDeployment   = copy.deployment,
      this.formEditBuild        = copy.standard_build,
      this.formEditPublic       = copy.public,
      this.formEditInternal     = copy.internal,
      this.formEditVersion      = copy.version,
      this.formEditPrimaryPOC   = copy.primary_poc,
      this.formEditSecondaryPOC = copy.secondary_poc,
      this.formEditDeveloper    = copy.developer;
    },
    editService(i) {
      let fD = new FormData();

      fD.append(`active`,         this.formEditActive);
      fD.append(`name`,           this.formEditName);
      fD.append(`public`,         this.formEditPublic);
      fD.append(`description`,    this.formEditDescription);
      fD.append(`url`,            this.formEditUrl);
      fD.append(`deployment`,     this.formEditDeployment);
      fD.append(`standard_build`, this.formEditBuild);
      fD.append(`internal`,       this.formEditInternal);
      fD.append(`version`,        this.formEditVersion);
      fD.append(`developer`,      this.formEditDeveloper);

      this.$axios
      .patch(`${process.env.api}${process.env.service}${this.formEditID}/`,fD)
      .then((res) => {
        this.$refs.editServiceModal[i].showModal = false;
        this.loadServices();
        console.log(`%c editService 👌 `, this.consoleLog.success);
      })
      .catch((e) => {
        console.log(e);
        console.log(`%c editService 🛑 `, this.errLogStyle);
      });
    },
    getEmployees() {
      this.$axios
      .get(`${process.env.api}${process.env.employee}`)
      .then((res) => {
        this.employees = res.data;
        console.log(`%c getEmployees 👌 `, this.consoleLog.success);
      })
      .catch((e) => {
        console.log(e);
        console.log(`%c getEmployees 🛑 `, this.errLogStyle);
      });
    },
  },
}
</script>

<style lang="scss" scoped>
  fieldset {
    legend {
      color: $text-color;
      font-size: 16px;
      margin-bottom: 8px !important;
      font-weight: $weight-semi-bold;
      width: 100%;
    }

    label {
      color: $text-color;
      font-size: 16px;
      margin: 0 0 0 8px !important;
      font-weight: $weight-normal;
      width: 100%;
    }
  }

  .search {
    position: absolute;
    top: 0;
    width: 100%;
    background-color: lighten($color-slate, 25%);
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 50px 0 0 0;
    z-index: 0;

    .wrapper {
      position: relative;
      border-top-left-radius: ($radius-default * 2);
      border-top-right-radius: ($radius-default * 2);
      background: rgba(255, 255, 255, 0.08%);
      padding: 50px 100px;
      width: 750px;
      margin: 0;

      p {
        position: absolute;
        bottom: 15px;
        color: white;
        font-style: italic;
      }
    }

    /deep/ .field-group {
      width: 100%;
      margin: 0;

      label {
        color: rgba(255, 255, 255, 0.9%);
      }
    }
  }

  .results-wrapper {
    width: 100%;
    height: calc(100vh - 320px);
    position: fixed;
    overflow-y: scroll;
    top: 312px;
    bottom: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;

    .results-header {
      background-color: #f2f2f2;
      position: fixed;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      z-index: 1;
      border-right: 1px solid darken($color-grey-lighter, 7%);
      border-bottom: 1px solid darken($color-grey-lighter, 7%);
      border-left: 1px solid darken($color-grey-lighter, 7%);
      width: 750px;
      padding: 20px;

      h4 {
        width: auto;
        color: lighten($text-color, 15%);
        font-size: 20px;
        line-height: 24px;
      }

      div {
        &:last-of-type {
          margin-left: auto;
        }
      }

      /deep/ button {
        height: fit-content;
        display: flex;
        align-items: center;
        align-self: center;
        background-color: $color-green;
        margin: 0;
        padding: 10px 15px;

        &:hover {
          background-color: darken($color-green, 5%);
        }
      }
    }

    .sidebar {
      text-align: right;
      position: fixed;
      transform: translate(-295%);
      left: 50%;
      top: 330px;
      width: 200px;

      p {
        margin: 0 0 10px 0;

        small {
          display: block;
          font-size: 14px;
          color: #8f8f8f;
          font-weight: 600;
        }

        &:first-of-type {
          margin: 0 0 10px 0;
        }
      }

      /deep/ button {
        margin: 0 0 10px 0;
      }

      .status-legend {
        span {
          display: flex;
          flex-wrap: wrap;
          width: 100px;
          padding: 2px;
          margin: 0 0 10px auto;

          &:last-of-type {
            margin: 0 0 0 auto;
          }
        }
      }
    }

    .results {
      width: 750px;
      overflow-y: scroll;
      position: absolute;
      top: 86px;
      background-color: rgba(255, 255, 255, 0.5%);
      border-bottom-left-radius: ($radius-default * 2);
      border-bottom-right-radius: ($radius-default * 2);

      h4 {
        // background-color: green;
        color: lighten($text-color, 25%);
        width: 100%;
        padding: 20px;
        text-align: center;
        font-size: 20px;
        line-height: 24px;
      }

      .no-results {
        display: flex;
        flex-wrap: wrap;
        padding: 20px 0;

        h4 {
          // background-color: red;
          display: inline-block;
          width: fit-content;
        }

        button {
          height: fit-content;
          display: flex;
          align-items: center;
          align-self: center;
          background-color: $color-green;
          margin: 0 20px 0 auto;
          padding: 10px 15px;

          &:hover {
            background-color: darken($color-green, 5%);
          }
        }
      }
    }
  }

  .loading {
    padding: 20px;

    h4 {
      text-align: left;
      font-size: 20px;
      line-height: 20px;
    }
  }

  .result {
    // background-color: pink;
    margin: 0;
    padding: 15px 20px;
    display: flex;
    align-items: flex-start;
    flex-wrap: wrap;
    border-bottom: 1px solid darken($color-grey-lighter, 7%);
    border-left: 10px solid;

    &.active {
      border-left-color: $color-green;
    }

    &.inactive {
      border-left-color: $text-color;
    }

    &:hover {
      background-color: rgba(255, 255, 255, 15%);
    }

    &:last-of-type {
      border-bottom: none;
    }

    .col {
      display: flex;
      flex-wrap: wrap;
      flex-direction: column;

      &:nth-of-type(1) {
        // background-color: orange;
        width: 250px;
      }

      &:nth-of-type(2),
      &:nth-of-type(3){
        // background-color: green;
        width: 150px;
        margin-left: 20px;
        padding-left: 20px;
        border-left: 1px solid darken($color-grey-lighter, 7%);
      }

      &:last-of-type {
        // background-color: red;
        margin-left: auto;
        flex-direction: row;
        align-items: center;

        div {
          width: auto;
          padding: 0;
          margin: 0;
          border: none;
        }

        /deep/ button {
          margin: 0 0 0 10px;
          padding: 0;
          background-color: transparent;
          color: lighten($text-color, 20%);

          // &:hover {
          //   background-color: darken($color-green, 5%);
          // }
        }
      }
    }

    p {
      color: $text-color;
      font-weight: $weight-semi-bold;

      &:not(:first-of-type) {
        font-size: 14px;
        color: lighten($text-color, 25%);
      }
    }
  }

  .search-wrapper {
    position: relative;
    height: 350px;

    .field-group {
      z-index: 10;
      position: relative;
      margin: 20px 0;
    }

    ul {
      &.search-results {
        margin: 0;
        padding: 0;
        list-style: none;
        z-index: 1;
        background-color: white;
        filter: drop-shadow(0 1px 2px rgba(0,0,0,0.2));
        height: auto;
        max-width: 100%;
        width: 100%;
        max-height: 350px;
        position: absolute;
        top: 60px;
        border: 1px solid lighten($text-color, 50%);
        border-bottom-left-radius: $radius-default;
        border-bottom-right-radius: $radius-default;

        li {
          display: flex;
          padding: 8px;

          &:nth-child(even) {
            background: rgba($color-cloud, 0.3);
          }

          &:hover {
            /deep/ button {
              color: $color-green;
            }
          }

          .edit-wrapper {
            .actions {
              display: flex;
              margin: 0 10px 0 0;
              border-right: 1px solid lighten($text-color, 50%);

              /deep/ button {
                color: lighten($text-color, 20%);
                margin: 0 20px 0 0;
                line-height: 16px;
                padding: 0;
                background-color: transparent;
              }
            }
          }
        }

        button {
          color: lighten($text-color, 20%);
          margin-left: auto;

          &:hover {
            color: $color-green;
          }
        }
      }
    }
  }

  .editServiceModal,
  .addServiceModal {
    /deep/ .modal-wrapper {
      .modal-container {
        width: 675px;

        .modal-body {
          max-height: 650px;
        }
      }
    }
  }

  .removeServiceManagerModal {
    /deep/ .modal-wrapper {
      .modal-container {
        width: 375px;

        .modal-body {
          max-height: 150px;
        }
      }
    }
  }

  /deep/ .modal-wrapper {
    .modal-container {

      .modal-body {

        form {
          // background-color: blue;
          display: flex;
          align-items: flex-start;

          .field-group,
          .form-group {
            margin-bottom: 20px;
          }

          .left {
            width: 200px;
            padding: 0 20px 0 0;

            ul {
              margin: 0 0 20px 0;
              padding: 0;
              list-style: none;

              li {
                color: $text-color;
                padding: 4px 8px;
                display: flex;
                align-items: center;

                &:first-child {
                  margin: 0 0 10px 0;
                  padding: 0;

                  &:hover {
                    background-color: white;
                  }

                  button {
                    padding: 0 8px;
                    font-size: 14px;
                    color: white;
                    background-color: $color-blue;
                  }
                }

                &:nth-child(even) {
                  background: rgba($color-cloud, 0.3);
                }

                &:hover {
                  background-color: lighten($color-grey, 10%);
                  color: $text-color;
                }

                div {
                  &:last-of-type {
                    margin-left: auto;
                  }
                }

                span {
                  &.label {
                    margin: 0 !important;
                    font-size: 16px;
                  }
                }
              }
            }

            .field-group,
            .form-group {
              width: auto;
              padding: 0;

              fieldset {
                padding: 0;

                div {
                  &:first-of-type {
                    margin: 0 10px 0 0;
                  }
                }
              }
            }
          }

          .right {
            flex: 1;
            padding: 0 0 0 20px;
            border-left: 1px solid darken($color-grey-lighter, 7%);
            // background-color: yellow;
          }
        }
      }

      /deep/ .modal-footer{
        display: flex;
        align-items: center;

        p {
          margin-left: auto;
          color: $text-color;
        }

        .button,
        button {
          color: white !important;
          padding: 5px 15px !important;
          margin: 0 10px 0 0 !important;
          border-radius: $radius-default !important;

          &:hover {
            color: white !important;
          }

          &.confirm {
            background-color: $color-green !important;

            &:hover {
              background-color: darken($color-green, 5%) !important;
            }
          }

          &.cancel {
            background-color: $color-orange-darker !important;

            &:hover {
              background-color: darken($color-orange-darker, 5%) !important;
            }
          }
        }
      }
    }
  }
</style>