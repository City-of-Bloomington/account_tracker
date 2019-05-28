<template>
  <aside>
    <h1 v-if="asideHeader">
      <strong>Finished: </strong> {{asideHeader}}
    </h1>

    <h1 v-if="!asideHeader">Overview</h1>

    <p v-if="!stepOneData">Details of the <strong>User Creation Wizard</strong> will appear here as they become available.</p>

    <ul>
      <template v-if="stepOneData">
        <li class="step-title" v-if="stepOneData">Step One</li>
        <li v-if="stepOneData" class="name">
          <strong>Name:&nbsp;</strong>
          <div v-if="cropped" class="profile-image">
            <img :src="cropped" alt="">
          </div>
          <span v-if="first">{{first}}&nbsp;</span>
          <span v-if="nickname">({{nickname}})&nbsp;</span>
          <span v-if="middle">{{middle}}&nbsp;</span>
          <span v-if="last">{{last}}</span><!--
          --><span v-if="(suffix)">, {{suffix}}</span>
        </li>
      </template>

      <template v-if="stepTwoData">
        <li class="step-title" v-if="stepTwoData">Step Two</li>
        <li v-if="facility">
          <strong>Facility:&nbsp;</strong>
          <span v-html="facility"></span>
        </li>

        <li v-if="department.name">
          <strong>Dept.:&nbsp;</strong>
          <span v-html="department.name"></span>
        </li>

        <li v-if="group.name">
          <strong>Group:&nbsp;</strong>
          <span v-html="group.name"></span>
        </li>

        <li v-if="job.name">
          <strong>Job:&nbsp;</strong>
          <span v-html="job.name"></span>
        </li>

        <li v-if="job.salaryGroup">
          <strong>Salary Group:&nbsp;</strong>
          <span v-html="job.salaryGroup"></span>
        </li>

        <li v-if="job.clockInRequired">
          <strong>Clock-In Required:&nbsp;</strong>
          <span v-html="job.clockInRequired"></span>
        </li>

        <!-- <li v-if="status">
          <strong>Status:&nbsp;</strong>
          <span v-html="status"></span>
        </li> -->

        <li v-if="startDate">
          <strong>Start Date:&nbsp;</strong>
          <span v-html="dateFormatted"></span>
        </li>
      </template>

      <template v-if="stepThreeData">
        <li class="step-title" v-if="stepThreeData">Step Three</li>
        <li v-if="supervisor">
          <strong>Supervisor:&nbsp;</strong>
          <span v-html="supervisor"></span>
        </li>

        <li v-if="supervisorPhone">
          <strong>Supervisor Phone:&nbsp;</strong>
          <span v-html="supervisorPhone"></span>
        </li>

        <li v-if="employeePhone">
          <strong>Employee Phone:&nbsp;</strong>
          <span v-html="employeePhone"></span>
        </li>
      </template>

      <template v-if="selectedServiceRequestNames">
         <li class="step-title" v-if="selectedServiceRequestNames">Step Four</li>
        <li v-if="selectedServiceRequestNames">
          <strong>Services:&nbsp;</strong>
          <span v-html="selectedServiceRequestNames"></span>
        </li>
      </template>

      <template v-if="extraQuestionAnswers.length">
        <li class="step-title">Step Five</li>
        <li>
          <strong>Extras:&nbsp;</strong>
          <ul class="extras">
            <li v-for="qa,i in extraQuestionAnswers">
              {{qa.replace(/\\|\//g,'')}}
            </li>
          </ul>
        </li>
      </template>
    </ul>
  </aside>
</template>

<script>
import {
  mapState,
  mapMutations,
  mapGetters,
  mapActions }        from 'vuex'
import {
  mapFields }         from 'vuex-map-fields';

import moment         from 'moment'

export default {
  props: ['stepActive', 'asideHeader'],
  data() {
    return {}
  },
  computed: {
    stepOneData() {
      if(this.cropped  ||
         this.first    ||
         this.nickname ||
         this.middle   ||
         this.last     ||
         this.suffix)
        return true;
    },
    stepTwoData() {
      if(this.facility      ||
         this.department.id ||
         this.group.id      ||
         this.job.id        ||
         this.startDate)
        return true;
    },
    stepThreeData() {
      if(this.supervisor      ||
         this.supervisorPhone ||
         this.employeePhone)
        return true;
    },
    customHeader() {
      if (this.asideHeader.length) {
        return true
      } else {
        return false
      }
    },
    dateFormatted() {
      let formatted = moment(this.startDate)
                      .format(this.startDateFormat);
      return formatted;
    },
    // serviceNamesArrayToString() {
    //   return this.selectedServiceRequestNames.join(', ')
    // },
    ...mapFields([
      'startDateFormat',
      'authUser',

      'createUser.totalSteps',

      'createUser.image.cropped',

      'createUser.name.first',
      'createUser.name.middle',
      'createUser.name.last',
      'createUser.name.suffix',
      'createUser.name.nickname',

      'createUser.facility',
      'createUser.department',
      'createUser.group',
      'createUser.job',
      'createUser.startDate',


      'createUser.supervisor',
      'createUser.supervisorPhone',
      'createUser.employeePhone',

      'createUser.selectedServiceRequestNames',

      'createUser.extraQuestionAnswers',
    ])
  },
  methods: {}
}
</script>

<style lang="scss" scoped>
  aside {
    color: $text-color;
    width: 350px;
    margin: 0 40px 0 0;
    height: calc(100vh - 240px);

    h1 {
      color: $text-color;
      font-size: $size-l;
      line-height: $size-l;
      font-weight: $weight-semi-bold;
      margin: 0 0 $space-s 0;
      padding: 0 0 $space-s 0;
      border-bottom: 1px solid lighten($text-color, 50%);
    }

    p {
      color: $text-color;
      font-size: 18px;
      font-style: italic;
      line-height: $line-height-m;
      margin: 0;
    }

    button {
      background-color: lighten($text-color, 10%);
      margin: 0 0 10px 0;

      &:last-child {
        margin: 0;
      }
    }

    ul {
      height: calc(100% - 45px);
      overflow-y: scroll;
      list-style: none;
      padding: 0;
      margin: $space-s 0 0 0;

      li {
        display: flex;
        margin: 0 0 10px 6px;
        font-weight: $weight-semi-bold;
        color: lighten($text-color, 15%);

        &.name {
          align-items: center;

          .profile-image {
            height: 30px;
            width: 30px;
          }
        }

        &.divider {
          border-bottom: 1px solid lighten($text-color, 50%);
        }

        &.step-title {
          margin: 20px 0 10px 0;
          padding: 5px 10px;
          background-color: $color-blue;
          font-size: $size-m;
          color: white;
          border-radius: $radius-default;

          &:nth-of-type(1) {
            margin: 0 0 10px 0;
          }
        }

        strong {
          color: darken($text-color, 5%);
          margin: 0 0 2px -5px;
        }

        ul {
          margin: 0;
        }
      }
    }

    .wrapper {
      border-top: 1px solid lighten($text-color, 50%);
      margin: 20px 0 0 0;
      padding: 20px 0 0 0;
    }
  }
</style>