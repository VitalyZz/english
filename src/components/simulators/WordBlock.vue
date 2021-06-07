<template>
  <div class="words">
    <div class="word-block" v-for="(el, index) in words" :key="el" :style="{backgroundColor: el.color}">
      <div class="number">{{ index + 1 }}</div>
      <div class="word">{{ el.word }}</div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['words']
}
</script>

<style scoped lang="scss">
  .word-block {
    display: flex;
    align-items: center;
    box-shadow: 8px 8px 10px rgba(0, 0, 0, 0.7);
    border-radius: 5px;
    padding: 10px 20px;

    &:not(:last-child) {
      margin-bottom: 15px;
    }

    .number {
      color: #fff;
      font-weight: bold;
    }

    .word {
      text-transform: uppercase;
      font-size: 18px;
      margin-left: 20px;
      color: #fff;
    }
  }
</style>