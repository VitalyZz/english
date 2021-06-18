<template>
  <div class="dropDownMenu" ref="dropDownMenu" :style="{left: x + 'px', top: y + 'px'}">
    <div
      v-for="dictionary in dictionaries"
      :key="dictionary.id_dictionary"
      class="dictionary"
      @click="$emit('selectDictionary', dictionary.id_dictionary, dictionary.title)"
    >
      {{ dictionary.title }}
    </div>
  </div>
</template>

<script>
export default {
  emits: ['selectDictionary'],
  props: ['x', 'y', 'dictionaries'],
  mounted() {
    const div = this.$refs.dropDownMenu;
    const width = this.$refs.dropDownMenu.clientWidth;
    div.style.left = this.x - width / 2 + 'px';
    console.log(div.style.left)
  },
}
</script>

<style scoped lang="scss">
  .dropDownMenu {
    position: absolute;
    font-size: 16px;
    color: #fff;
    background-color: #465361;
    border: 2px solid #252c32;

    .dictionary {
      padding: 7px 15px;
      cursor: pointer;
      transition: .3s;

      &:hover {
        background-color: #5f7089;
      }
    }

    .dictionary:not(:last-child) {
      border-bottom: 2px solid #36404A;
    }
  }
</style>