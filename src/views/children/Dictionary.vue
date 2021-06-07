<template>
  <h1>Слова {{ $route.params.id }}</h1>
  <div v-for="(word, index) in words" :key="word" class="main">
    <div class="index">{{ index + 1 }}</div>
    <div class="word">{{ word }}</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      words: ['Hello', 'table', 'word']
    }
  }
}
</script>

<style scoped lang="scss">
  .main {
    display: flex;
    align-items: center;

    .index {
      font-size: 18px;
    }

    .word {
      margin-left: 10px;
      font-size: 28px;
    }
  }
</style>