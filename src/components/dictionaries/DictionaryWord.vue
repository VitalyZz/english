<template>
  <div class="index">{{ index + 1 }}</div>
  <div class="word">{{ word.word }} - {{ word.translation }}</div>
  <button class="delete" @click="deleteWord(word.id_word_information)">Удалить</button>
  <div class="list">
    <select v-model="selectedTask" @change="onChangeSelectedTask">
      <option
          v-for="dictionary in dictionaries"
          :key="word.id_word_information"
          :value="word.id_word_information + ',' + dictionary.id_dictionary"
          :selected="word.id_dictionary === dictionary.id_dictionary"
          :data-type="selectedTask"
      >
        {{ dictionary.title }}
      </option>
    </select>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ['word', 'index', 'dictionaries'],
  data() {
    return {
      // selectedTask: this.word.id_word_information + ',' + this.dictionaries.find(el => el.id_dictionary === this.word.id_dictionary).id,
      selectedTask: this.word.id_word_information + ',' + this.word.id_dictionary,
    }
  },
  methods: {
    async onChangeSelectedTask() {
      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      const id_word_information = this.selectedTask.split(',')[0];
      const id_dictionary = this.selectedTask.split(',')[1];

      const data = { id_user, id_word_information, id_dictionary }

      await axios.post('/word/updateByChangeDictionary', data)
      console.log('onChangeSelectedTask:', this.selectedTask, id_word_information, id_dictionary);
    },
    async deleteWord(id_word_information) {
      const id_dictionary = this.$route.params.id;

      if (['known', 'study'].indexOf(id_dictionary) !== -1) {
        const id_user = this.$store.getters['auth/getCurrentUser'].id;
        this.words = this.words.filter(el => el.id_word_information !== id_word_information)
        await axios.post('/words/deleteWithoutDictionaryById', { id_user, id_word_information })
      } else {
        const id_user = this.$store.getters['auth/getCurrentUser'].id;
        this.words = this.words.filter(el => el.id_word_information !== id_word_information)
        await axios.post('/words/delete', { id_user, id_dictionary, id_word_information })
      }
    }
  },
}
</script>

<style scoped lang="scss">
  .index {
    font-size: 18px;
    color: #fff;
  }

  .word {
    margin-left: 10px;
    font-size: 28px;
    padding: 5px 10px;
    background-color: #8296aa;
    border-radius: 3px;
    transition: .3s;
    cursor: pointer;

    &:hover {
     box-shadow: 0 0 10px 1px #000;
    }
  }

  .delete {
    padding: 8px 15px;
    font-size: 16px;
    outline: none;
    background-color: #ff4747;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    margin-left: 10px;
    color: #fff;
    cursor: pointer;

    &:hover {
     box-shadow: 0 0 10px 1px #ff4747;
    }
  }

  .list {
    margin-left: 10px;

    select {
      border-radius: 5px;
      background-color: #2bb1ff;
      padding: 8px 15px;
      font-size: 16px;
      color: #fff;
      font-weight: bold;
      border: none;
      outline: none;
    }
  }
</style>