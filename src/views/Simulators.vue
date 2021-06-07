<template>
  <div class="simulators">
    <SimulatorBlock
      v-for="title of titles"
      :key="title.id"
      :element="title"
    />
  </div>
</template>

<script>
import SimulatorBlock from "@/components/simulators/SimulatorBlock";

export default {
  name: 'simulators',
  data() {
    return {
      titles: this.$store.getters['simulators/getTitlesOfSimulators'],
    }
  },
  components: {
    SimulatorBlock
  }
}
</script>

<style scoped lang="scss">
  .simulators {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }
</style>