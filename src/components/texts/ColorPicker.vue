<template>
  <div class="colorWrapper">
    <div class="picker" :style="{backgroundColor: currentColor}" @click="pickerHandle" v-click-outside="close"></div>
    <ColourPicker
        v-click-outside="close"
        theme="dark"
        :color="currentColor"
        :sucker-hide="false"
        :sucker-canvas="suckerCanvas"
        :sucker-area="suckerArea"
        @changeColor="changeColor"
        @openSucker="openSucker"
        :showPicker="showPicker"
    />
    <div class="title" :style="{color: currentColor}" @click="pickerHandle" v-click-outside="close"><slot></slot></div>
  </div>
</template>

<script>
import ColourPicker from '@caohenghu/vue-colorpicker'

export default {
  props: {
    name: {
      type: String,
      required: true
    },
    color: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      suckerCanvas: null,
      suckerArea: [],
      isSucking: true,
      showPicker: false,
      currentColor: this.color
    }
  },
  mounted() {
    // this.currentColor = '#000'
    // console.log('Color', this.currentColor)
  },
  methods: {
    close() {
      if (this.showPicker) {
        this.showPicker = false
        this.writeColor();
      }
    },
    pickerHandle() {
      this.showPicker = !this.showPicker
    },
    writeColor() {
      console.log('writeColor ColorPicker', this.currentColor);
      localStorage.setItem('color_' + this.name, this.currentColor);
    },
    changeColor(color) {
      const { r, g, b, a } = color.rgba
      this.currentColor = `rgba(${r}, ${g}, ${b}, ${a})`
    },
    openSucker(isOpen) {
      console.log('openSucker isOpen', isOpen);
      if (isOpen) {
        // ... canvas be created
        // this.suckerCanvas = canvas
        // this.suckerArea = [x1, y1, x2, y2]
      } else {
        this.suckerCanvas && this.suckerCanvas.remove
      }
    }
  },
  components: {
    ColourPicker,
  },
}
</script>

<style scoped lang="scss">
  .colorWrapper {
    display: flex;
    align-items: center;
    margin-bottom: 10px;

    .picker {
      border: none;
      width: 20px;
      height: 20px;
      border-radius: 3px;
      cursor: pointer;
    }

    .title {
      margin-left: 10px;
      cursor: pointer;
    }
  }
</style>