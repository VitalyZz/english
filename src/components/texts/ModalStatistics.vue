<template>
  <div class="main">
    <div>Количество слов: {{ wordsAll }}</div>
    <div>Количество уникальных слов: {{ wordsUnique }}</div>
    <div>Количество известных слов: {{ wordsKnown }}</div>
    <div>Количество изучаемых слов: {{ wordsStudy }}</div>
    <div>Количество неизвестных слов: {{ wordsUnknown }}</div>
    <div @click="$emit('closeModalStatistics')" class="close"></div>
  </div>

  <div class="dark" @click="$emit('closeModalStatistics')"></div>
</template>

<script>
export default {
  emits: ['closeModalStatistics'],
  data() {
    return {
      wordsAll: 0,
      wordsUnique: 0,
      wordsKnown: 0,
      wordsStudy: 0,
      wordsUnknown: 0,
    }
  }
}
</script>

<style scoped lang="scss">
  .main {
    display: flex;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #36404A;
    z-index: 10;
    color: #fff;
    flex-direction: column;
    justify-content: center;
    padding: 20px 40px;
    font-size: 23px;
    border-radius: 5px;

    .close {
      position: absolute;
      color: #fff;
      top: -19px;
      right: -19px;
      cursor: pointer;
      width: 20px;
      height: 20px;
      background-color: #FF4D4D;
      padding: 6px;
      border-radius: 100%;
      border: 3px solid #fff;
      transition: all .3s;

      &:before, &:after {
        position: absolute;
        content: '';
        width: 3px;
        height: 20px;
        background-color: #fff;
        top: calc(50% - 10px);
        right: calc(50% - 2px);
      }

      &:before {
        transform: rotate(-45deg);
      }

      &:after {
        transform: rotate(45deg);
      }

      &:hover {
        border-color: #FF4D4D;
        transition: all .3s;
      }

      &:hover:after, &:hover:before {
        background-color: #fff;
        transition: all .3s;
      }
    }
  }

  .dark {
    display: block;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    position: absolute;
    top: 0;
    left: 0;
    z-index: 5;
  }
</style>