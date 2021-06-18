<template>
  <div class="main">
    <div>Количество слов: <LoaderMini v-if="isLoading"/> <span v-else>{{ wordsAll }}</span></div>
    <div>Количество уникальных слов: <LoaderMini v-if="isLoading"/> <span v-else>{{ wordsUnique }}</span></div>
    <div>Количество известных слов: <LoaderMini v-if="isLoading"/> <span v-else>{{ wordsKnown }}</span></div>
    <div>Количество изучаемых слов: <LoaderMini v-if="isLoading"/> <span v-else>{{ wordsStudy }}</span></div>
    <div>Количество неизвестных слов: <LoaderMini v-if="isLoading"/> <span v-else>{{ wordsUnknown }}</span></div>
    <div @click="$emit('closeModalStatistics')" class="close"></div>
  </div>

  <div class="dark" @click="$emit('closeModalStatistics')"></div>
</template>

<script>
import LoaderMini from "@/components/app/LoaderMini";
import axios from "axios";

export default {
  props: ['id_text', 'publicAccess'],
  emits: ['closeModalStatistics'],
  data() {
    return {
      wordsAll: 0,
      wordsUnique: 0,
      wordsKnown: 0,
      wordsStudy: 0,
      wordsUnknown: 0,
      isLoading: true,
    }
  },
  async mounted() {
    const id_user = this.$store.getters['auth/getCurrentUser'].id;
    const id_text = this.id_text

    let text = '';

    if (this.publicAccess === '1') {
      text = (await axios.post('/text/getByIdPublic', { id_text })).data.text
    } else {
      text = (await axios.post('/text/getById', { id_user, id_text })).data.text
    }

    const resultKnown = (await axios.post('/words/getByMeaning', { id_user, meaning: 0 })).data;
    const resultStudy = (await axios.post('/words/getByMeaning', { id_user, meaning: 1 })).data;

    const arrOfWordsKnown = resultKnown.map(el => el.word);
    const arrOfWordsStudy = resultStudy.map(el => el.word);

    let countOfWords = 0;
    let countKnownWords = 0;
    let countStudyWords = 0;
    let countUnknownWords = 0;
    let countUniqueWords = 0;

    const words = text.match(/(?:[a-z])+/igm);

    console.log('words', words)

    if (words === null) {
      countOfWords = 0;
      countKnownWords = 0;
      countStudyWords = 0;
      countUnknownWords = 0;
      countUniqueWords = 0;
    } else {
      words.forEach(word => {
        if (arrOfWordsKnown.indexOf(word.toLowerCase()) !== -1) countKnownWords++; // проверям является ли слово известным
        else if (arrOfWordsStudy.indexOf(word.toLowerCase()) !== -1) countStudyWords++; // проверям является ли слово изучаемым
        else countUnknownWords++; // иначе слово неизвестное
      })

      countOfWords = countKnownWords + countStudyWords + countUnknownWords;
      countUniqueWords = [...new Set(words)].length;
    }

    this.wordsAll = countOfWords;
    this.wordsUnique = countUniqueWords;
    this.wordsKnown = countKnownWords;
    this.wordsStudy = countStudyWords;
    this.wordsUnknown = countUnknownWords;

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