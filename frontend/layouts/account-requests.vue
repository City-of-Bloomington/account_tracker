<template>
  <div class="account-requests-wrapper">
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

.account-requests-wrapper {
  background: $color-grey-lighter;

  /deep/ .page-wrapper {
    background: white;
    position: fixed;
    top: 115px;
    padding: 20px;
    height: calc(100vh - 135px);
    width: 100%;
    overflow: hidden;
  }
}
</style>