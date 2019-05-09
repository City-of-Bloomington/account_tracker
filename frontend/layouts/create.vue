<template>
  <div class="create-wrapper">
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

.create-wrapper {
  /deep/ .page-wrapper {
    position: fixed;
    width: 100%;
    top: 115px;
    padding: 20px;
    background-color: white;
  }

  /deep/ .page-inner-wrapper {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    padding: 20px 0 0 0;
  }

  /deep/ form {
    flex-grow: 1;
    flex: 1;

    h1 {
      display: flex;
      color: $text-color;
      font-size: $size-l;
      line-height: $size-l;
      margin: 0 0 $space-m 0;
      padding: 0 0 $space-s 0;
      border-bottom: 1px solid lighten($text-color, 50%);

      small {
        margin-left: auto;
      }
    }

    .wrapper {
      // background-color: yellow;
      display: flex;
      flex-wrap: wrap;
      width: 100%;
      height: calc(100vh - 310px);
      overflow-y: hidden;
    }
  }
}
</style>