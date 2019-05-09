<template>
  <div>
    <progressStepper
      :step-active="stepActive"
      :previous-button="previous"
      :next-button="next" />

    <div class="page-inner-wrapper">
      <asideComponent />

      <form>
        <h1><strong>Step Three:</strong>&nbsp;Supervisor information</h1>

        <div class="field-group">
          <label for="supervisor">Supervisor</label>
          <select name="supervisor"
                  id="supervisor"
                  type="select"
                  v-model="supervisor">
            <option v-for="(item, index) in groupManagers"
                    :value="item.value">
              {{ item.text }}
            </option>
          </select>
        </div>

        <div class="field-group">
          <label for="supervisor-phone">Supervisor Phone</label>
          <select name="supervisor-phone"
                  id="supervisor-phone"
                  type="select"
                  v-model="supervisorPhone">
            <option v-for="(item, index) in managersPhone"
                    :value="item.value">
              {{ item.text }}
            </option>
          </select>
        </div>

        <fn1-input v-model="employeePhone"
                   label="Employee Phone (desk)"
                   @keyup.native="yooo"
                   @blur.native="yooo"
                   type="tel"
                   placeholder="888-888-8888"
                   pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                   maxlength="10"
                   name="employee-phone"
                   id="employee-phone" />
      </form>
    </div>
  </div>
</template>

<script>
import {
  mapState,
  mapMutations,
  mapGetters,
  mapActions }          from 'vuex'
import {
  mapFields }           from 'vuex-map-fields';

import axios            from 'axios'

import progressStepper  from '~/components/progressStepper'
import asideComponent   from '~/components/asideComponent'
import exampleSelect    from '~/components/exampleSelect'

export default {
  layout:           'create',
  components: {
    progressStepper,
    asideComponent,
    exampleSelect
  },
  data() {
    return {
      stepActive:   3,
      previous:     { name: 'create-two'},
      next:         { name: 'create-four'},
      managers:     [],
      phoneValue:   0,
      preventIteration: false,
      managersPhone: [
        {"value": "123-456-7890", "text": "123-456-7890"},
        {"value": "098-765-4321", "text": "098-765-4321"}
      ]
    }
  },
  mounted() {
    this.$nextTick(() => {
      if(this.group.id) {
        this.getGroupManagers();
      }
    });
  },
  watch: {
  },
  methods: {
    yooo(event) {
      if (['Arrow', 'Backspace', 'Shift']
         .includes(event.key)) {
          this.preventIteration = true;
        return;
      }
      if (this.preventIteration) {
          this.preventIteration = false;
        return;
      }
      this.phoneValue = this.employeePhone
                        .replace(/-/g, '')
                        .match(/(\d{1,10})/g)[0];

      this.employeePhone = this.phoneValue
      .replace(/(\d{1,3})(\d{1,3})(\d{1,4})/g, '$1-$2-$3');
    },
    getGroupManagers() {
      axios.get(`${process.env.ttApi}${process.env.managerService}?group_id=${this.group.id}`)
      .then((res) => {
        this.managers = res.data;
      })
      .catch((error) => {
        console.log(error);
      })
    }
  },
  computed: {
    ...mapFields([
      'createUser.department',
      'createUser.facility',
      'createUser.division',
      'createUser.group',
      'createUser.supervisor',
      'createUser.supervisorPhone',
      'createUser.employeePhone'
    ]),
    groupManagers() {
      let managersByGroup = [];

      let groupManagers = this.managers.map(
        value => value.name
      );

      groupManagers.forEach((supervisor) => {
        managersByGroup.push({
          "text": supervisor,
          "value": supervisor
        });
      });

      return managersByGroup;
    },
  }
}
</script>

<style lang="scss" scoped>
  div {
    &:nth-of-type(2) {
      display: flex;
      flex-wrap: wrap;
    }
  }
</style>