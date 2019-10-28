<template>
  <component :is="type">
    <h2 v-if="pageTitle" v-html="pageTitle"></h2>
  </component>
</template>

<script>
/**
 * Used as the Header template.
 */
export default {
  name: "pageTitle",
  status: "ready",
  release: "1.0.0",
  props: {
    /**
     * The html element name used for the nav bar.
     * `header`
     */
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
    padding: 0 0 20px 0;
    margin: 0 0 40px 0;
    border-bottom: 1px solid lighten($text-color, 50%);

    h2 {
      color: $text-color;
      font-weight: $weight-semi-bold;
    }
  }
</style>

<docs>
</docs>