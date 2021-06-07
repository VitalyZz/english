<template>
  <div class="main">
    <div class="wrapper-left-block">
      <div class="translates">{{ getTranslates }}</div>
      <div class="writeWord">
        <input type="text" class="inputWriteWord">
      </div>
      <div class="sentences">
        <div class="sentence" v-for="sentence in getSentences">
          {{ sentence }}
        </div>
      </div>
    </div>
    <div class="translate-block">
      <button class="donotknow" @click="$emit('idonotknow')">Я не знаю</button>
      <button class="forward" @click="$emit('answer')">Дальше</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ['tempCurrentWord', 'tempSentences', 'tempCurrentBlock', 'right', 'wrong'],
  data() {
    return {
      translates: ''
    }
  },
  mounted() {

  },
  computed: {
    getTranslates() {
      return this.tempCurrentBlock.translate.join(', ')
    },
    getSentences() {
      const regex = new RegExp(`${this.tempCurrentWord}?.`, 'gi');
      return this.tempSentences.map(el => el.replace(regex, '???'))
    }
  }
}
</script>

<style scoped lang="scss">
.main {
  display: flex;
  background: #2B2B2B;
  box-shadow: 8px 8px 10px rgba(0, 0, 0, 0.7);
  border-radius: 5px;
  padding: 20px 30px;

  .wrapper-left-block {
    display: flex;
    flex-direction: column;
    justify-content: space-between;

    .translates {
      font-size: 18px;
      color: #fff;
      text-transform: uppercase;
    }

    .writeWord {

      .inputWriteWord {
        border: none;
        background-color: #fff;
        font-size: 18px;
        font-weight: bold;
        padding: 10px 15px;
        outline: none;
        border-radius: 5px;
        margin: 10px 0;
        color: #2B2B2B;
        text-transform: uppercase;
      }
    }

    .sentences {

      .sentence {
        color: #FFFFFF;
        font-size: 20px;

        &:not(:last-child) {
          margin-bottom: 5px;
        }
      }
    }
  }

  .translate-block {
    display: flex;
    flex-direction: column;
    margin-left: 50px;

    .translate {
      padding: 10px 70px;
      background: #36404A;
      border-radius: 100px;
      font-size: 22px;
      font-weight: bold;
      color: #fff;
      border: none;
      cursor: pointer;

      &:not(:last-child) {
        margin-bottom: 10px;
      }

      &:hover {
        background: #2f475f;
      }
    }

    .forward, .donotknow {
      border-radius: 100px;
      padding: 10px 70px;
      font-size: 22px;
      font-weight: bold;
      margin-top: 30px;
      border: none;
      cursor: pointer;
    }

    .donotknow {
      background: #FF4D4D;
      &:hover {
        background: #ff6969;
      }
    }

    .forward {
      background: #00A307;
      &:hover {
        background: #00A307;
      }
    }
  }
}
</style>