<template>
  {{ word.before }}
  <span
    :class="word.class"
    :style="[highlight === 'color' ? { color: word.color } : { backgroundColor: word.color }]"
    @click="$emit('wordHandler', word, $event)"
    @mouseover="mouseOver"
    @mouseout="mouseOut"
  >
    {{ word.text }}
  </span>
</template>

<script>
export default {
  emits: ['wordHandler'],
  props: {
    word: {
      type: Object,
      required: true
    },
    highlight: {
      type: String,
      required: true
    }
  },
  methods: {
    mouseOver(e) {
      if (this.highlight === 'color') {
        e.target.style.color = '#a1a1a1';
      } else {
        e.target.style.color = '#fff';
        e.target.style.backgroundColor = '#a1a1a1';
      }
    },
    mouseOut(e) {
      if (this.highlight === 'color') {
        e.target.style.color = this.word.color;
      } else {
        e.target.style.color = '#fff';
        e.target.style.backgroundColor = this.word.color;
      }
    }
  },
}
</script>

<style scoped lang="scss">
  .unknown, .known, .study {
    cursor: pointer;
    transition: .3s;
    color: #fff;

    &:hover {
      box-shadow: 0 0 1px 1px #a1a1a1;
      color: #a1a1a1;
     }
  }
</style>