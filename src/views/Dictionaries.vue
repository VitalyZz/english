<template>
  <div class="dictionaries">
    <DictionaryBlock
      :isDelete="false"
      :color="'#ff4b4b'"
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
        v-else
    />

    <CreateNewDictionary
        v-if="!showLoader"
        @click="showModalAddDictionary = true"
    />


    <ModalAddDictionary
      v-if="showModalAddDictionary"
      @closeModalDictionary="showModalAddDictionary = false"
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

    .wrapperLoader {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  }
</style>