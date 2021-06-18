<template>
  <div class="main">
    <h1 class="addText">Добавить текст</h1>
    <label class="title_label">Заголовок</label>
    <input type="text" class="title" v-model="title" autocomplete="off">
    <label class="text_label">Текст</label>
    <textarea class="text" cols="30" rows="10" v-model="text"></textarea>
    <div class="wrapperBtn">
      <button class="btnAddText" @click="addText">Добавить</button>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      title: '',
      text: ''
    }
  },
  methods: {
    async addText() {
      const title = this.title;
      const text = this.text;

      if (title.length >= 3 && text.length >= 5) {
        const id_user = this.$store.getters['auth/getCurrentUser'].id;

        const formData = { title, text, id_user, public: 1 };

        this.showModalAddText = false;
        await axios.post('/text/createAdmin', formData);

        this.title = '';
        this.text = '';
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .main {
    margin-top: 60px;
    display: flex;
    flex-direction: column;

    .addText {
      color: #fff;
    }

    .title_label, .text_label {
      font-size: 16px;
      font-weight: bold;
      color: #fff;
      margin: 30px 0 10px 0;
    }

    .title {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      background-color: transparent;
      outline: none;
      transition: .3s;
      border: 2px solid #0DFF92;
      color: #fff;
      font-size: 18px;
    }

    .text {
      resize: none;
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      background-color: transparent;
      outline: none;
      transition: .3s;
      border: 2px solid #0DFF92;
      color: #fff;
      font-size: 18px;
    }

    .wrapperBtn {
      margin-top: 20px;
      .btnAddText {
        display: block;
        padding: 5px 10px;
        border: 2px solid #0DFF92;
        background-color: transparent;
        color: #0DFF92;
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 20px;
        cursor: pointer;
        border-radius: 2px;
        transition: .3s;

        &:hover {
          background-color: #0DFF92;
          color: #36404A;
        }
      }
    }
  }
</style>