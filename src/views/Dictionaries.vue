<template>
  <div class="dictionaries">
    <DictionaryBlock
      :isDelete="false"
      :color="'#5574ff'"
      :title="'Изучаемые'"
      id="study"
    />

    <DictionaryBlock
      :isDelete="false"
      :color="'#00C108'"
      :title="'Известные'"
      id="known"
    />

    <div v-if="showLoader" class="wrapperLoader">
      <Loader/>
    </div>

    <DictionaryBlock
        v-for="dictionary in dictionaries"
        :key="dictionary.id_dictionary"
        :title="dictionary.title"
        :id="dictionary.id_dictionary"
        @deleteDictionary="deleteDictionary"
        v-else
    />

    <CreateNewDictionary
        v-if="!showLoader"
        @click="showModalAddDictionary = true"
    />

    <ModalAddDictionary
      v-if="showModalAddDictionary"
      @closeModalDictionary="showModalAddDictionary = false"
      @addNewDictionary="addNewDictionary"
    />
  </div>
</template>

<script>
import ModalAddDictionary from "@/components/dictionaries/ModalAddDictionary";
import DictionaryBlock from "@/components/dictionaries/DictionaryBlock";
import CreateNewDictionary from "@/components/dictionaries/CreateNewDictionary";
import Loader from "@/components/app/Loader";
import axios from "axios";

export default {
  name: 'dictionaries',
  data() {
    return {
      showModalAddDictionary: false,
      showLoader: true,
      dictionaries: []
    }
  },
  components: {
    DictionaryBlock,
    CreateNewDictionary,
    ModalAddDictionary,
    Loader
  },
  methods: {
    async addNewDictionary(title) {
      if (title.length > 5) {
        const id_user = this.$store.getters['auth/getCurrentUser'].id;
        const formData = { title, id_user }

        this.showModalAddDictionary = false

        this.dictionaries.push((await axios.post('/dictionary/create', formData)).data)
      }
    },
    async deleteDictionary(id_dictionary) {
      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      const data = { id_user, id_dictionary}

      this.dictionaries = this.dictionaries.filter(el => el.id_dictionary !== id_dictionary)

      await axios.post('/dictionary/delete', data);
    }
  },
  async mounted() {
    const id_user = this.$store.getters['auth/getCurrentUser'].id;

    this.dictionaries = (await axios.post('/dictionary/getAll', {id_user})).data

    this.showLoader = false
  }
}
</script>

<style scoped lang="scss">
  .dictionaries {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 60px;

    .wrapperLoader {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  }
</style>