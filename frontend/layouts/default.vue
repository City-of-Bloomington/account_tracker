<template>
  <div class="default-wrapper">
    <headerNav />

    <div class="page-wrapper">
      <nuxt />
    </div>
  </div>
</template>

<script>
import headerNav    from '~/components/headerNav'

export default {
  components: { headerNav },
  data() {
    return {}
  }
}
</script>

<style lang="scss" scoped>

.default-wrapper {
  /deep/ .page-wrapper {
    position: relative;
    top: 115px;
    padding: 20px;
    background-color: white;
  }
}
</style>