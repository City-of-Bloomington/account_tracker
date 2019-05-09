<template>
  <div class="services-wrapper">
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

.services-wrapper {
  background: $color-grey-lighter;

  /deep/ .page-wrapper {
    background: $color-grey-lighter;
    position: fixed;
    top: 99px;
    padding: 0;
    height: 100vh;
    width: 100%;
  }
}
</style>