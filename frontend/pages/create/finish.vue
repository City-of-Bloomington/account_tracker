<template>
  <div>
    <progressStepper
      v-if="!showSuccessMsg"
      :step-active="stepActive"
      :previous-button="previousStep"
      :next-button="next" />

    <div>
      <div class="success-wrapper">
        <h1 v-if="showSuccessMsg" v-html="successMsg"></h1>
      </div>

      <fn1-alert variant="danger" v-if="errorMsg.length">
        <template v-for="e, i in errorMsg">
          <template v-for="m, i in e">
            <p><strong>{{m.field}}: </strong> {{m.message}}</p>
          </template>
        </template>
      </fn1-alert>

      <asideComponent v-if="!showSuccessMsg"
                      :step-active="stepActive"
                      :aside-header="asideHeader" />

        <nav>
          <nuxt-link v-if="showSuccessMsg"
                     class="button"
                     :to="{ name: 'index'}">Return Home</nuxt-link>

          <div v-if="!showSuccessMsg">
            <button class="button create"
                    @click="accountRequestSubmit">
              Create
            </button>
          </div>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import {
  mapState,
  mapMutations,
  mapGetters,
  mapActions }         from 'vuex'
import { mapFields }   from 'vuex-map-fields'
import moment          from 'moment'

import progressStepper from '~/components/progressStepper'
import asideComponent  from '~/components/asideComponent'
import exampleCheckbox from '~/components/exampleCheckbox'

export default {
  layout:           'create',
  components: {
    progressStepper,
    asideComponent
  },
  data() {
    return {
      stepActive:   "finished",
      previous:     "",
      next:         "",
      responseMsg:  "",
      showSuccessMsg: false,
      successMsg:   "<strong>Thanks</strong>, we've got your request!",
      errorMsg:     [],
      asideHeader:  "Review & Create Account Request",
      imageID:      "",
      acctReqActionNew: "Account Request: New",
      acctReqActionNewMsg: "Account Request 'new' submission.",
    }
  },
  mounted() {
    // this.base64Strip();
  },
  computed: {
    dateFormatted() {
      let formatted = moment(this.startDate)
                      .format('YYYY-MM-DD');
      return formatted;
    },
    previousStep() {
      return true;
    },
    ...mapFields([
      'startDateFormat',
      'auth.authUser',

      'createUser.totalSteps',

      'createUser.image.full',
      'createUser.image.cropped',

      'createUser.name.first',
      'createUser.name.middle',
      'createUser.name.last',
      'createUser.name.suffix',
      'createUser.name.nickname',
      'createUser.startDate',
      'createUser.department',
      'createUser.status',

      'createUser.facility',
      'createUser.division',
      'createUser.group',
      'createUser.job',

      'createUser.supervisor',
      'createUser.supervisorPhone',
      'createUser.employeePhone',

      'createUser.selectedServiceRequestIds',

      'createUser.extraQuestionAnswers',
    ])
  },
  methods: {
    base64Strip() {
      this.full = this.full.replace(/^data:image\/[a-z]+;base64,/, "");
      this.cropped = this.cropped.replace(/^data:image\/[a-z]+;base64,/, "");
    },
    checkServices() {
      if(this.selectedServiceRequestIds.length) {
        return JSON.stringify(this.selectedServiceRequestIds)
      } else {
        return ''
      }
    },
    dataURItoBlob(dataURI) {
      if(dataURI) {
        var byteString;
        if (dataURI.split(',')[0].indexOf('base64') >= 0)
          byteString = atob(dataURI.split(',')[1]);
        else
          byteString = unescape(dataURI.split(',')[1]);

        var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

        var ia = new Uint8Array(byteString.length);
        for (var i = 0; i < byteString.length; i++) {
          ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ia], {type:mimeString});
      }
    },
    accountRequestSubmit() {
      let blobFull = this.dataURItoBlob(this.full);
      let blobCropped = this.dataURItoBlob(this.cropped);
      let firstLast = `${this.first}-${this.last}`;
      let flToLower = firstLast.toLowerCase();

      let fD = new FormData();
      fD.append(`full_image`, blobFull, `${flToLower}-full`);
      fD.append(`cropped_image`, blobCropped, `${flToLower}-cropped`);
      fD.append(`first_name`, this.first);
      fD.append(`middle_name`, this.middle);
      fD.append(`last_name`, this.last);
      fD.append(`suffix`, this.suffix);
      fD.append(`nickname`, this.nickname);
      fD.append(`employee_phone`, this.employeePhone);
      fD.append(`supervisor`, this.supervisor);
      fD.append(`supervisor_phone`, this.supervisorPhone);
      fD.append(`department`, this.department.name);
      fD.append(`division`, this.division);
      fD.append(`group`, this.group.name);
      fD.append(`facility`, this.facility);
      fD.append(`job`, this.job.name);
      fD.append(`employee_status`, this.job.salaryGroup);
      fD.append(`clock_entry_only`, this.job.clockInRequired);
      fD.append(`start_date`, this.dateFormatted);
      fD.append(`request_status`, `new`);
      fD.append(`requester`, this.authUser.id);
      fD.append(`requested_services`, this.checkServices());
      fD.append(`dynamic_options`, JSON.stringify(this.extraQuestionAnswers));

      this.$axios
      .post(`${process.env.api}${process.env.accountRequest}`,fD)
      .then((response) => {
        console.log(`accountRequestSubmit res :: `, response.data);

        let arID = response.data.id;

        this.$axios
        .post(`${process.env.api}${process.env.action}`,{
          "user":    this.authUser.id,
          "account": arID,
          "action":  this.acctReqActionNew,
          "comment": this.acctReqActionNewMsg
        })
        .then(response => {
          console.log(`ACTION AR new :: `, response)
        })
        .catch(e => {
          console.log(`ACTION AR new error :: `, e)
        });

        this.showSuccessMsg = true;
        this.stepActive = 1;
        this.$store.dispatch('createUser/resetCrUsrState');
      })
      .catch((e) => {
        this.errorMsg.push(e.response.data.errors)
        console.log(e.response.data.errors)
      });
    },
    clearStore() {

    }
  }
}
</script>

<style lang="scss" scoped>

  @keyframes shadow-pulse {
    0% {
      box-shadow: 0 0 0 0px rgba(76, 174, 79, 0.35);
    }
    100% {
      box-shadow: 0 0 0 20px rgba(76, 174, 79, 0);
    }
  }

  h1 {
    color: $text-color;
    font-size: $size-l;
    line-height: $size-l;
    margin: 0 0 $space-m 0;
    padding: 0 0 $space-s 0;
    border-bottom: 1px solid lighten($text-color, 50%);
  }

  .alert {
    width: 700px;
    margin: 20px auto 0 auto;
    display: block;
  }

  aside {
    // background-color: blue;
    width: 700px;
    height: auto;
    margin: 40px auto 0 auto;
    display: block;

    /deep/ .step-title {
      display: none;
    }

    /deep/ ul:not(.extras) {
      column-count: 2;

      // li {
      //   font-size: 18px;
      //   margin: 0 0 20px 0;

      //   strong {
      //     margin: 0;
      //   }
      // }
    }
  }

  nav {
    width: 500px;
    margin: 0 auto;
    display: block;
    text-align: center;

    .button {
      display: block;
      padding: 10px 20px;
      margin: 40px auto 0 auto;
      width: 350px;
      text-align: center;
      font-size: 18px;
      text-transform: uppercase;
      background-color: $text-color;

      &:hover {
        background-color: darken($text-color, 5%);
      }

      &.create {
        background-color: $color-green;
        animation: shadow-pulse 1s infinite;

        &:hover {
          background-color: darken($color-green, 5%);
        }
      }
    }
  }

  .success-wrapper {
    width: 500px;
    margin: 40px auto 0 auto;
    display: block;

    h1 {
      line-height: $size-xl;
    }
  }
</style>