<template>
  <component :is="type">
    <h2 v-if="pageTitle" v-html="pageTitle"></h2>

    <div class="buttons-slot">
      <slot name="buttons"></slot>
    </div>
  </component>
</template>

<script>
export default {
  name: "pageTitle",
  status: "ready",
  release: "1.0.0",
  props: {
    type: {
      type: String,
      default: "header",
      validator: value => {
        return value.match(/(header)/)
      },
    },
    pageTitle: {
      type: String,
      default: null,
    }
  },
}
</script>

<style lang="scss" scoped>
  header {
    display: flex;
    align-items: center;
    padding: 0 0 20px 0;
    margin: 20px 0;
    border-bottom: 1px solid lighten($text-color, 50%);

    h2 {
      width: auto;
      color: $text-color;
      font-weight: $weight-semi-bold;
    }

    .buttons-slot {
      margin-left: auto;

      // ::v-deep button, .button, a {
      //   -webkit-box-shadow: 0 16px 20px -13px rgba(42,44,48,.5);
      //   box-shadow: 0 16px 20px -13px rgba(42,44,48,.5);

      //   &:hover,
      //   &:focus,
      //   &.active {
      //     -webkit-box-shadow: 0 16px 20px -13px rgba(42,44,48,.75);
      //     box-shadow: 0 16px 20px -13px rgba(42,44,48,.75);
      //   }
      // }
    }
  }
</style>

<docs></docs>