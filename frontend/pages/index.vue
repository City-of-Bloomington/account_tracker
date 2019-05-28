<template>
  <div>
    <div class="search">
      <div class="wrapper">
        <fn1-input v-model="acctReqSearch"
                 label="Account Request Search"
                 placeholder="Search by Name, Department or Group"
                 name="acct-req-search"
                 id="acct-req-search" />

        <p v-if="filteredAcctReqs.length && acctReqSearch !== ''">
          {{filteredAcctReqs.length}} results
        </p>
      </div>
    </div>

    <div class="results-wrapper">
      <div class="status-legend">
        <fn1-badge v-for="s, i in requestStatuses"
                   :key="i"
                   :class="s">
          {{s}}
        </fn1-badge>
      </div>

      <div class="results">
        <div class="no-results" v-if="!filteredAcctReqs.length">
          <h4>No results for, <strong>"{{acctReqSearch}}"</strong>.</h4>
          <fn1-button @click.native="goCreateAccountRequest">New Account Request</fn1-button>
        </div>

        <div v-for="a, i in filteredAcctReqs"
             class="result"
             :class="a.request_status">
          <div class="profile-image" v-if="a.cropped_image">
            <img :src="a.cropped_image">
          </div>

          <div class="avatar" v-if="!a.cropped_image">
            {{ userInitial(a.first_name) }}{{ userInitial(a.last_name) }}
          </div>

          <div>
            <p class="label">
              <span v-if="a.first_name">{{a.first_name}}</span>
              <span v-if="a.nickname">{{a.nickname}}</span>
              <span v-if="a.middle_name">{{a.middle_name}}</span>
              <span v-if="a.last_name">{{a.last_name}}</span>
            </p>
            <p v-if="a.department">{{a.department}}</p>
            <p v-if="a.job">{{a.job}}</p>
          </div>

          <div>
            <p class="label">Last Update</p>
            <p v-if="a.updated">{{MMDYYYYDateFormat(a.updated)}}</p>
            <p v-if="a.updated">{{timeAgo(a.updated)}}</p>
          </div>

          <div>
            <fn1-button @click.native="viewAccountRequest(a)">view</fn1-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapFields }   from 'vuex-map-fields'
import axios           from 'axios'

export default {
  layout:           'search',
  data() {
    return {
      acctReqSearch: '',
    }
  },
  mounted() {
    this.getAccountRequests();
  },
  methods: {
    goCreateAccountRequest() {
      this.$router.push(this.paths.createAccountRequest);
    },
    viewAccountRequest(id) {
      if(this.authLevel.admin && id.request_status == "new") {
        this.$router.push(
          {
            name: `account-requests`,
            params: { new: id.id }
          }
        );
        console.log(this.$router);
      } else {
        this.$router.push(this.paths.accountRequests + id.id);
      }
    }
  },
  computed: {
    ...mapFields([
      'paths',
      'requestStatuses',
      'consoleLog',
      'acctReqs.accountRequests'
    ]),
    allAccountRequests() {
      let master = [...this.accountRequests.approved,...this.accountRequests.denied,...this.accountRequests.new,...this.accountRequests.pending];
      return master
    },
    filteredAcctReqs() {
      if(this.acctReqSearch.length) {
        return this.allAccountRequests
        .filter(user => {
          let fullName   = user.full_name.toLowerCase(),
          firstName      = user.first_name.toLowerCase(),
          middleName     = user.middle_name.toLowerCase(),
          lastName       = user.last_name.toLowerCase(),
          userDept       = user.department.toLowerCase(),
          userGroup      = user.group.toLowerCase();

          return fullName.includes(this.acctReqSearch.toLowerCase()) ||
                 firstName.includes(this.acctReqSearch.toLowerCase()) ||
                 middleName.includes(this.acctReqSearch.toLowerCase()) ||
                 lastName.includes(this.acctReqSearch.toLowerCase()) ||
                 userDept.includes(this.acctReqSearch.toLowerCase()) ||
                 userGroup.includes(this.acctReqSearch.toLowerCase()) ||
                 fullName.includes(this.acctReqSearch.toLowerCase())
        })
        .sort((a, b) => new Date(b.requested) - new Date(a.requested))
      } else {
        return this.allAccountRequests
        .sort((a, b) => new Date(b.requested) - new Date(a.requested))
      }
    },
  }
}
</script>

<style lang="scss" scoped>

  .search {
    position: absolute;
    top: 0;
    width: 100%;
    background-color: $color-blue;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 50px 0 0 0;
    z-index: 1;

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
    top: 325px;
    bottom: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;

    .status-legend {
      position: fixed;
      transform: translate(-495%);
      left: 50%;
      top: 343px;
      width: 100px;

      span {
        display: flex;
        flex-wrap: wrap;
        width: 100px;
        margin: 0 0 10px auto;

        &:last-of-type {
          margin: 0 0 0 auto;
        }
      }
    }

    .results {
      width: 750px;
      overflow-y: scroll;
      position: absolute;
      top: 0;
      // bottom: 0;
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

  .result {
    // background-color: pink;
    margin: 0;
    padding: 15px 20px;
    display: flex;
    flex-wrap: wrap;
    border-bottom: 1px solid darken($color-grey-lighter, 7%);
    border-left: 10px solid;

    &.new {
      border-left-color: $color-ucla-gold-dark;
    }

    &.pending {
      border-left-color: $color-blue;
    }

    &.approved {
      border-left-color: $color-green-light;
    }

    &.active {
      border-left-color: $color-green;
    }

    &.denied {
      border-left-color: $color-vermilion-darker;
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

    div {
      display: flex;
      flex-wrap: wrap;
      flex-direction: column;

      &:nth-of-type(2) {
        // background-color: red;
        width: 300px;
        margin-right: 20px;
        padding-right: 20px;
        border-right: 1px solid darken($color-grey-lighter, 7%);
      }

      &:last-of-type {
        justify-content: center;
        margin-left: auto;

        button {
          background-color: $color-green;

          &:hover {
            background-color: darken($color-green, 5%);
          }
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

    .avatar {
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: lighten($color-blue, 45%);
      margin: 0 20px 0 0;
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }

    .profile-image {
      margin: 0 20px 0 0;
      width: 50px;
      height: 50px;
    }
  }
</style>