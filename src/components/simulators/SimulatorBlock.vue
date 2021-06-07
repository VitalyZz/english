<template>
  <div class="simulator">
    <div class="title">{{ element.name }}</div>
    <router-link :to="'/simulator/' + element.id" class="forward-btn">Перейти</router-link>
  </div>
</template>

<script>
export default {
  props: ['element']
}
</script>

<style scoped lang="scss">
  .simulator {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 70px 40px;
    margin-top: 30px;
    width: 270px;
    height: 220px;
    box-sizing: border-box;
    position: relative;
    align-items: center;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 5px;

    .title {
      font-size: 22px;
      font-weight: bold;
      color: #fff;
      text-align: center;
    }

    .forward-btn {
      position: absolute;
      bottom: 20px;
      padding: 5px 25px;
      border: 3px solid #FFFFFF;
      border-radius: 30px;
      background-color: transparent;
      font-weight: bold;
      font-size: 18px;
      color: #fff;
      cursor: pointer;
      transition: .3s;
      
      &:hover {
        background-color: #fff;
        color: #36404A;
      }
    }
  }
</style>