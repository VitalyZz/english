<template>
  <div class="main">
    <div>Количество слов: <LoaderMini v-if="isLoading"/> <span v-else>{{ wordsAll }}</span></div>
    <div v-if="!isMeaning">Количество известных слов: <LoaderMini v-if="isLoading"/> <span v-else>{{ wordsKnown }}</span></div>
    <div v-if="!isMeaning">Количество изучаемых слов: <LoaderMini v-if="isLoading"/> <span v-else>{{ wordsStudy }}</span></div>
    <div>Количество правильных ответов: <LoaderMini v-if="isLoading"/> <span v-else>{{ correct }}</span></div>
    <div>Количество неправильных ответов: <LoaderMini v-if="isLoading"/> <span v-else>{{ wrong }}</span></div>
    <div>Количество повторений: <LoaderMini v-if="isLoading"/> <span v-else>{{ repetitions }}</span></div>
    <div @click="$emit('closeModalStatistics')" class="close"></div>
  </div>

  <div class="dark" @click="$emit('closeModalStatistics')"></div>
</template>

<script>
import LoaderMini from "@/components/app/LoaderMini";
import axios from "axios";

export default {
  props: ['id_dictionary'],
  emits: ['closeModalStatistics'],
  data() {
    return {
      wordsAll: 0,
      wordsKnown: 0,
      wordsStudy: 0,
      correct: 0,
      wrong: 0,
      repetitions: 0,
      isLoading: true,
      isMeaning: false,
    }
  },
  async mounted() {
    const id_user = this.$store.getters['auth/getCurrentUser'].id;
    const id_dictionary = this.id_dictionary;

    const data = { id_user, id_dictionary };

    if (['known', 'study'].indexOf(id_dictionary) !== -1) {
      console.log('HWEHWEHOWUWOEEOH');
      this.isMeaning = true
    }

    const statistics = (await axios.post('/dictionary/statistics', data)).data;

    console.log('statistics', statistics)

    this.wordsAll = statistics.words ?? 0;
    this.wordsKnown = statistics.known ?? 0;
    this.wordsStudy = statistics.study ?? 0;
    this.correct = statistics.correct ?? 0;
    this.wrong = statistics.wrong ?? 0;
    this.repetitions = statistics.repetitions ?? 0;

    this.isLoading = false
  },
  components: {
    LoaderMini
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

    .element {
      display: flex;
      align-items: center;
      margin-right: 5px;
    }

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