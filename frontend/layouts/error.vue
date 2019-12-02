<template>
  <div class="container">
    <fn1-alert variant="warning">
      <strong>Status Code: {{ error.statusCode }}</strong>
      - <strong>{{ error.message }}</strong>
    </fn1-alert>
    
    <nuxt-link
      class="button"
      to="/">Go Home</nuxt-link>
  </div>
</template>

<script>
export default {
  props: ['error']
}
</script>

<style lang="scss">
  .container {
    margin: 20px 0 0 0;
  }
</style>