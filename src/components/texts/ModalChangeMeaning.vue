<template>
  <div class="modalMenu" :style="{left: x + 'px', top: y + 'px'}">
    <div class="addStudy" @click="$emit('modalMenuHandler', 'study')">Learn</div>
    <div class="addKnow" @click="$emit('modalMenuHandler', 'known')">Know</div>
    <div class="AddUnknown" @click="$emit('modalMenuHandler', 'unknown')">Unknown</div>
  </div>
</template>

<script>
export default {
  emits: ['modalMenuHandler'],
  props: ['x', 'y']
}
</script>

<style scoped lang="scss">
  .modalMenu {
    display: block;
    padding-bottom: 10px;
    background-color: #000;
    border-radius: 0 0 10px 10px;
    box-shadow: 3px 3px 5px black;
    position: absolute;
    top: 5px;
    left: 0;
    transition: all .3s;
    text-align: center;

    .addStudy, .addKnow, .AddUnknown {
      font-size: 18px;
      font-weight: bold;
      display: block;
      padding: 5px;
      cursor: pointer;

      &:hover {
        background-color: #2B2B2B;
      }
    }

    .addStudy {
      color: #436dff;
    }

    .addKnow {
      color: #5dff34;
    }

    .AddUnknown {
      color: #ff392e;
    }
  }
</style>