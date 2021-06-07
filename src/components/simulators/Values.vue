<template>
  <div class="values">
    <div class="correct">
      <div class="title">Правильно</div>
      <div class="value">{{ right }}</div>
    </div>
    <div class="wrong">
      <div class="title">Ошибки</div>
      <div class="value">{{ wrong }}</div>
    </div>
    <div class="completed">
      <div class="title">Выполнено</div>
      <div class="value">8 / 10</div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['right', 'wrong']
}
</script>

<style scoped lang="scss">
  .values {
    //position: absolute;
    //top: 0;
    //right: -180px;

    .correct, .wrong, .completed {
      background: #2B2B2B;
      box-shadow: 8px 8px 10px rgba(0, 0, 0, 0.7);
      border-radius: 5px;
      padding: 10px 15px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;

      &:not(:last-child) {
        margin-bottom: 10px;
      }

      .title, .value {
        font-weight: bold;
        font-size: 20px;
      }

      .title {
        color: #fff;
      }

      .value {
        color: #0DFF92;
        margin-top: 5px;
      }
    }
  }
</style>