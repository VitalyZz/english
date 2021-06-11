<template>
  <div v-if="!showLoader">
    <h1 v-if="words.length > 0">Слова:</h1>
    <h1 v-else>Слова отсутствуют</h1>
  </div>

  <div v-if="showLoader" class="wrapperLoader">
    <Loader/>
  </div>

  <div v-else v-for="(word, index) in words" :key="word.id_word" class="main">
    <DictionaryWord
      :word="word"
      :index="index"
      :dictionaries="dictionaries"
    />
  </div>
</template>

<script>
import axios from "axios";
import DictionaryWord from "@/components/dictionaries/DictionaryWord";
import Loader from "@/components/app/Loader";

export default {
  data() {
    return {
      words: [],
      dictionaries: [],
      showLoader: true
    }
  },
  async mounted() {
    const id_user = this.$store.getters['auth/getCurrentUser'].id;
    const id_dictionary = this.$route.params.id;

    this.dictionaries = (await axios.post('/dictionary/getAll', {id_user})).data

    if (['known', 'study'].indexOf(id_dictionary) !== -1) {
      const values = {
        'study': 1,
        'known': 0,
      }
      const data = {
        id_user,
        meaning: values[this.$route.params.id]
      }
      this.words = (await axios.post('/words/getByMeaningWithTranslations', data)).data
    } else {
      const data = {
        id_user,
        id_dictionary
      }
      this.words = (await axios.post('/words/getByDictionaryId', data)).data
    }

    this.showLoader = false
  },
  components: {
    DictionaryWord,
    Loader
  }
}
</script>

<style scoped lang="scss">
  h1 {
    margin: 20px 0;
    color: #fff;
  }

  .wrapperLoader {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .main {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  }
</style>