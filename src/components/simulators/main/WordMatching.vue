<template>
  <div class="main">
    <div class="right-block">
      <button class="translate" v-for="el in tempWords">{{ el.word }}</button>
    </div>
    <div class="left-block">
      <button class="translate" v-for="el in tempWords">{{ el.translate }}</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ['tempCurrentWord', 'tempSentences', 'tempCurrentBlock', 'right', 'wrong', 'tempWords'],
  mounted() {
    console.log(this.tempSentences)
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

    .left-block, .right-block {
      display: flex;
      flex-direction: column;
      margin-left: 50px;

      .translate {
        padding: 10px 70px;
        border-radius: 100px;
        font-size: 22px;
        font-weight: bold;
        color: #fff;
        border: none;
        cursor: pointer;

        &:not(:last-child) {
          margin-bottom: 10px;
        }
      }
    }

    .right-block {
      .translate {
        background: #429fff;

        &:hover {
          background: #007dff;
        }
      }
    }

    .left-block, {
      .translate {
        background: #36404A;

        &:hover {
          background: #2f475f;
        }
      }
    }
  }
</style>