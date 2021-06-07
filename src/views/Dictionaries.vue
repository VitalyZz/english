<template>
  <div class="dictionaries">
    <DictionaryBlock
      :isDelete="false"
      :title="'Изучаемые'"
      :id="11"
    />

    <DictionaryBlock
      :isDelete="false"
      :color="'#00C108'"
      :title="'Известные'"
      :id="42"
    />

    <DictionaryBlock
      :color="'#00bad9'"
      :title="'Словарь №1'"
      :id="55"
    />

    <DictionaryBlock
      :color="'#b900e2'"
      :title="'Словарь №2'"
      :id="23"
    />

    <CreateNewDictionary
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

export default {
  name: 'dictionaries',
  data() {
    return {
      showModalAddDictionary: false
    }
  },
  components: {
    DictionaryBlock,
    CreateNewDictionary,
    ModalAddDictionary
  }
}
</script>

<style scoped lang="scss">
  .dictionaries {
    display: flex;
    flex-wrap: wrap;
  }
</style>