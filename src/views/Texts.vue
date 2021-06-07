<template>
  <div class="title">
    <h1>Мои тексты</h1>
  </div>
  <div class="btn_block">
    <button class="create-text" @click="showModalAddText = true">Добавить новый текст</button>
  </div>

  <div v-if="showLoader" class="wrapperLoader">
    <Loader/>
  </div>

  <div class="texts_block" v-else>
    <TextBlock
        v-for="text in texts"
        :key="text.id_text"
        :title="text.title"
        :text="text.text"
        :id_user="text.id_user"
        :id_text="text.id_text"/>
  </div>

  <teleport to="body">
    <ModalAddText
        v-if="showModalAddText"
        @closeModalAddText="showModalAddText = false"
    />
  </teleport>
</template>

<script>
import TextBlock from "@/components/texts/TextBlock";
import ModalAddText from "@/components/texts/ModalAddText";
import Loader from "@/components/app/Loader";
import axios from "axios";

export default {
  name: 'texts',
  data() {
    return {
      texts: [],
      showModalAddText: false,
      showLoader: true
    }
  },
  async mounted() {
    this.showLoader = true

    // get translates

    // const word = 'Conquered'
    // const url = `https://owlbot.info/api/v4/dictionary/${word}`;
    // const token = "073b418a9db1fcde565807b88a9f2ad25a30cd25";
    //
    // const params = {
    //   method: 'GET',
    //   headers: {
    //     'Authorization': 'Token ' + token
    //   }
    // }
    // const data = await fetch(url, params);
    // const result = await data.json();
    //
    // console.log('owlbot info', result);

    // get translates

    // get yandex START

    // const key = "dict.1.1.20210601T151729Z.cb8f7f225a387d74.67724ff1c9c838097dba9aace6250395f85202d2";
    // const words = (await axios.get('/words/getAll')).data

    // for (const el of words) {
    //   const url = `https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key=${key}&lang=en-ru&text=${el.word}`;
    //   const data = await fetch(url);
    //   const result = await data.json();
    //
    //   let translations = [];
    //
    //   result.def.forEach(el => {
    //     el.tr.forEach(el2 => {
    //       translations.push(el2.text)
    //     })
    //   })
    //
    //   translations = translations.slice(0, 4);
    //
    //   for (const translation of translations) {
    //     await axios.post('/words/insertTranslation', {translation, id_word: el.id_word})
    //   }
    // }

    // get yandex END

    const id_user = this.$store.getters['auth/getCurrentUser'].id;
    const response = await axios.post('/texts/get', {id_user: id_user})
    this.texts = response.data

    // Парсинг текста [START]



    // Парсинг текста [END]

    this.showLoader = false
  },
  components: {
    TextBlock,
    ModalAddText,
    Loader
  }
}
</script>

<style scoped lang="scss">
  .title {
    margin-top: 30px;

    h1 {
      font-weight: bold;
      font-size: 64px;
      line-height: 74px;
      color: #0DFF92;
      text-align: center;
    }
  }

  .wrapperLoader {
    display: flex;
    justify-content: center;
  }

  .btn_block {
    button.create-text {
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

  .texts_block {
    margin-bottom: 40px;
  }
</style>