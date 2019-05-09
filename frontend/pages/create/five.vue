<template>
  <div>
    <progressStepper
      :step-active="stepActive"
      :previous-button="previous"
      :next-button="next" />

    <div class="page-inner-wrapper">
      <asideComponent />

      <form>
        <h1><strong>Step Five:</strong> Extra information</h1>

        <div class="wrapper">
          <div class="dept-questions" v-if="!hasDeptExQuestions">
            <h1>Dept. Questions</h1>
            <div class="inner-wrapper">
              <template v-for="(question, index) in exDeptQuestions">
                <template v-if="question.type == 'select'">
                  <div class="field-group">
                    <label :for="question">{{question.text}}</label>
                    <select v-model="exDeptQsSelects[question.text]"
                            :name="question"
                            :id="question"
                            type="select">
                      <option>---</option>
                      <option v-for="(value, index) in question.value"
                              :value="value">
                        {{ value }}
                      </option>
                    </select>
                  </div>
                </template>

                <template v-if="question.type == 'text'">
                  <fn1-input v-model="exDeptQsTexts[question.text]"
                             :key="index"
                             :label="question.text"
                             :placeholder="question.text"
                             :name="question.text"
                             :id="question.text" />
                </template>

                <template v-if="question.type == 'number'">
                  <fn1-input type="number"
                             v-model="exDeptQsNumbers[question.text]"
                             :label="question.text"
                             :placeholder="question.text"
                             :name="question.text"
                             :id="question.text" />
                </template>

                <template v-if="question.type == 'textarea'">
                  <div class="field-group">
                    <label :for="question.text">{{question.text}}</label>
                    <textarea type="textarea"
                              v-model="exDeptQsTextareas[question.text]"
                              :id="question.text"
                              :name="question.text"
                              :placeholder="question.text"></textarea>
                  </div>
                </template>
              </template>
            </div>
          </div>

          <div class="group-questions" v-if="!hasGroupExQuestions">
            <h1>Group Questions</h1>
            <div class="inner-wrapper">
              <template v-for="(question, index) in exGroupQuestions">
                <template v-if="question.type == 'select'">
                  <div class="field-group">
                    <label :for="question">{{question.text}}</label>
                    <select v-model="exGroupQsSelects[question.text]"
                            :name="question"
                            :id="question"
                            type="select">
                      <option>---</option>
                      <option v-for="(value, index) in question.value"
                              :value="value">
                        {{ value }}
                      </option>
                    </select>
                  </div>
                </template>

                <template v-if="question.type == 'text'">
                  <fn1-input v-model="exGroupQsTexts[question.text]"
                             :key="index"
                             :label="question.text"
                             :placeholder="question.text"
                             :name="question.text"
                             :id="question.text" />
                </template>

                <template v-if="question.type == 'number'">
                  <fn1-input type="number"
                             v-model="exGroupQsNumbers[question.text]"
                             :label="question.text"
                             :placeholder="question.text"
                             :name="question.text"
                             :id="question.text" />
                </template>

                <template v-if="question.type == 'textarea'">
                  <div class="field-group">
                    <label :for="question.text">{{question.text}}</label>
                    <textarea type="textarea"
                              v-model="exGroupQsTextareas[question.text]"
                              :id="question.text"
                              :name="question.text"
                              :placeholder="question.text"></textarea>
                  </div>
                </template>
              </template>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations, mapGetters, mapActions } from 'vuex'
import { mapFields } from 'vuex-map-fields';

import progressStepper from '~/components/progressStepper'
import asideComponent   from '~/components/asideComponent'

export default {
  layout:           'create',
  components: {
    progressStepper,
    asideComponent
  },
  data() {
    return {
      stepActive:   5,
      previous:     { name: 'create-four'},
      next:         { name: 'create-finish'},

      exDeptQsSelects:       {},
      exDeptQsTexts:         {},
      exDeptQsTextareas:     {},
      exDeptQsNumbers:       {},

      exGroupQsSelects:       {},
      exGroupQsTexts:         {},
      exGroupQsTextareas:     {},
      exGroupQsNumbers:       {},
    }
  },
  mounted() {
    this.hasDeptExQuestions;
    this.hasGroupExQuestions;
  },
  watch: {
    'extraAnswers': {
      handler: function (val, oldVal) {}
    }
  },
  methods: {},
  computed: {
    ...mapFields([
      'createUser.totalSteps',
      'createUser.extraQuestionAnswers',

      'createUser.extraDeptQuestions',
      'createUser.extraDeptQuestionAnswers',
      // 'createUser.extraDeptQuestionAnswers.exDeptQsSelects',
      // 'createUser.extraDeptQuestionAnswers.exDeptQsTexts',
      // 'createUser.extraDeptQuestionAnswers.exDeptQsTextareas',
      // 'createUser.extraDeptQuestionAnswers.exDeptQsNumbers',

      'createUser.extraGroupQuestions',
      'createUser.extraGroupQuestionAnswers',
      // 'createUser.extraGroupQuestionAnswers.exGroupQsSelects',
      // 'createUser.extraGroupQuestionAnswers.exGroupQsTexts',
      // 'createUser.extraGroupQuestionAnswers.exGroupQsTextareas',
      // 'createUser.extraGroupQuestionAnswers.exGroupQsNumbers',
    ]),
    hasDeptExQuestions() {
      return this.isEmpty(this.extraDeptQuestions);
    },
    hasGroupExQuestions() {
      return this.isEmpty(this.extraGroupQuestions);
    },
    exDeptQuestions() {
      return JSON.parse(this.extraDeptQuestions);
    },
    exGroupQuestions() {
      return JSON.parse(this.extraGroupQuestions);
    },
    extraAnswers() {
      let joinedAnswers = {
              ...this.exDeptQsSelects,
              ...this.exDeptQsTexts,
              ...this.exDeptQsTextareas,
              ...this.exDeptQsNumbers,
              ...this.exGroupQsSelects,
              ...this.exGroupQsTexts,
              ...this.exGroupQsTextareas,
              ...this.exGroupQsNumbers
             }

      var res = Object.keys(joinedAnswers)
        .map(function(key) {
          return `${key}: ${JSON.stringify(joinedAnswers[key])}`;
        });

      return this.extraQuestionAnswers = res;
    }
  }
}
</script>

<style lang="scss" scoped>
  div {
    &:nth-of-type(2) {
      display: flex;
      flex-wrap: wrap;
      // background-color: red;
    }
  }

  form {
    // background-color: blue;
    display: flex;
    flex-wrap: wrap;
    // align-self: flex-start;

    .field-group {
      width: calc(100% - 6px);
      margin: 0 auto;
      padding: 40px 16px;
      border-bottom: 1px solid lighten($text-color, 50%);
      display: flex;
      flex-wrap: wrap;
      align-items: center;

      &:first-of-type {
        padding: 20px 16px;
      }

      &:nth-child(even) {
        background: rgba($color-cloud, 0.3);
      }

      &:last-of-type {
        border-bottom: none;
      }
    }

    .dept-questions,
    .group-questions {
      align-self: flex-start;
      flex: 1;
      max-width: 50%;

      h1 {
        margin: 0;
      }
    }

    .dept-questions {
      margin: 0 0 0 0;
      padding: 0 40px 0 0;
    }

    .group-questions {
      padding: 0 0 0 40px;
      border-left: 1px solid lighten($text-color, 50%);
    }

    .button-wrapper {
      width: 100%;
    }

    .inner-wrapper {
      // background-color: green;
      height: calc(100vh - 375px);
      overflow-y: scroll;
    }
  }

  /deep/ fieldset {
    /deep/ legend {
      color: $text-color;
      font-size: 18px;
    }

    input {
      &[type="checkbox"],
      &[type="radio"] {
        color: $text-color;
        margin: 0 5px 0 0;
      }
    }

    label {
      width: auto;
      color: $text-color;
      font-size: $size-m;
      margin: 0 !important;
    }
  }
</style>