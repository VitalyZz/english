<template>
  <div class="highlight_wrapper">
    <div class="outer" :style="{borderColor: color}">
      <div class="inner" :style="{backgroundColor: color}"></div>
    </div>
    <span class="text" :style="{color: color}"><slot></slot></span>
  </div>
</template>

<script>
export default {
  props: {
    isHighlight: {
      type: Boolean,
      default: false,
      required: false,
    }
  },
  computed: {
    color() {
      return this.isHighlight === true ? '#0DFF92' : '#A2ACBF'
    }
  }
}
</script>

<style scoped lang="scss">
  .highlight_wrapper {
    display: flex;
    align-items: center;
    margin-left: 10px;

    .outer {
      background-color: transparent;
      border: 3px solid;
      width: 20px;
      height: 20px;
      border-radius: 100%;
      position: relative;
      display: inline-block;
      margin-right: 10px;
      cursor: pointer;

      .inner {
        width: 12px;
        height: 12px;
        border-radius: 100%;
        position: absolute;
        top: calc(50% - 6px);
        left: calc(50% - 6px);
      }
    }

    .text {
      cursor: pointer;
    }
  }
</style>