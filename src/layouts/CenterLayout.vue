<template>
  <div class="container">
    <router-view/>
  </div>
</template>

<script>
export default {

}
</script>

<style scoped lang="scss">
  .container {
    min-height: calc(100vh - 70px);
    padding-top: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    flex-direction: column;
    width: 1300px;
    height: 100%;
  }
</style>