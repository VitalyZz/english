<template>
  <div class="main">
    <form action="" @submit.prevent="$emit('addNewDictionary', title)">
      <label for="title">Название словаря</label>
      <input type="text" id="title" name="title" v-model="title" autocomplete="off">
      <button>Создать</button>
    </form>
    <div @click="$emit('closeModalDictionary')" class="close"></div>
  </div>

  <teleport to="body">
    <div class="dark" @click="$emit('closeModalDictionary')"></div>
  </teleport>
</template>

<script>
export default {
  emits: ['closeModalDictionary', 'addNewDictionary'],
  data() {
    return {
      title: '',
    }
  },
}
</script>

<style scoped lang="scss">
.main {
  display: flex;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #36404A;
  z-index: 5;
  color: #fff;
  flex-direction: column;
  justify-content: center;
  padding: 20px 40px;
  font-size: 23px;

  form {
    display: flex;
    flex-direction: column;
    align-items: flex-start;

    label {

    }

    input {
      padding: 10px;
      border-radius: 5px;
      background-color: transparent;
      outline: none;
      transition: .3s;
      border: 2px solid #0DFF92;
      color: #fff;
      font-size: 18px;
      margin: 10px 0;
    }

    button {
      background-color: transparent;
      color: #0DFF92;
      font-size: 18px;
      font-weight: bold;
      line-height: 18px;
      border: 2px solid #0DFF92;
      box-sizing: border-box;
      border-radius: 3px;
      padding: 5px 10px;
      cursor: pointer;

      &:hover {
        color: #36404A;
        background-color: #0DFF92;
      }
    }
  }

  .close {
    position: absolute;
    color: #fff;
    top: -19px;
    right: -19px;
    cursor: pointer;
    width: 20px;
    height: 20px;
    background-color: #FF4D4D;
    padding: 6px;
    border-radius: 100%;
    border: 3px solid #fff;
    transition: all .3s;

    &:before, &:after {
      position: absolute;
      content: '';
      width: 3px;
      height: 20px;
      background-color: #fff;
      top: calc(50% - 10px);
      right: calc(50% - 2px);
    }

    &:before {
      transform: rotate(-45deg);
    }

    &:after {
      transform: rotate(45deg);
    }

    &:hover {
      border-color: #FF4D4D;
      transition: all .3s;
    }

    &:hover:after, &:hover:before {
      background-color: #fff;
      transition: all .3s;
    }
  }
}

.dark {
  display: block;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  position: absolute;
  top: 0;
  left: 0;
  z-index: 2;
}
</style>