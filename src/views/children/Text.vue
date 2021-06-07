<template>
  <div class="main">
    <div class="leftSide">
      <div class="block-change-statistics">
        <div class="img-document">
          <img :src="img_document" alt="document">
          <span>Текст</span>
        </div>
        <button class="change" @click="showModalEditor = true">Изменить</button>
        <button class="statistics" @click="showModalStatistics = true">Статистика</button>
      </div>

      <div class="highlight">
        <RadioButton :isHighlight="radioBtnText" @click="radioBtnHandle">Выделять текст</RadioButton>
        <RadioButton :isHighlight="radioBtnBackground" @click="radioBtnHandle">Выделять задний фон текста</RadioButton>
      </div>

      <div class="colors" v-if="isDataLoaded">
        <ColorPicker v-for="picker in colorPickers" :key="picker.index" :name="picker.name" :color="picker.currentColor">{{ picker.text }}</ColorPicker>
        <button class="default" @click="setDefaultColors">Установить по умолчанию</button>
      </div>

<!--      <div class="changeTheme">-->
<!--        <div class="changeTheme_title">Изменить тему</div>-->
<!--        <div class="wrapper_toggle">-->
<!--          <div class="toggle">-->
<!--            <span class="toggle_title">ON</span>-->
<!--            <div class="toggle_circle"></div>-->
<!--          </div>-->
<!--          <span class="dartTheme">Темная тема</span>-->
<!--        </div>-->
<!--      </div>-->

    </div>
    <div v-if="showLoader" class="wrapperLoader">
      <Loader/>
    </div>
    <div class="rightSide" v-else>
      <p class="text">{{ text }}</p>
    </div>
  </div>

  <teleport to="body">
    <ModalStatistics
        v-if="showModalStatistics"
        @closeModalStatistics="showModalStatistics = false"
        :statistics="statistics"
    />
  </teleport>

  <teleport to="body">
    <ModalEditor
        v-if="showModalEditor"
        @closeModalEditor="closeModalEditor"
        :text="text"
    />
  </teleport>
</template>

<script>
import RadioButton from "@/components/app/RadioButton";
import ColorPicker from "@/components/texts/ColorPicker";
import ModalStatistics from "@/components/texts/ModalStatistics";
import ModalEditor from "@/components/texts/ModalEditor";
import Loader from "@/components/app/Loader";
import img_document from '@/assets/images/document.svg';
import axios from "axios";

export default {
  data() {
    return {
      img_document,
      radioBtnText: true,
      radioBtnBackground: false,
      colorPickers: [
        {name: 'unknown', text: 'Цвет для неизвестных слов', defaultColor: '#ff392e', currentColor: null, index: 0},
        {name: 'known', text: 'Цвет для известных слов', defaultColor: '#5dff34', currentColor: null, index: 1},
        {name: 'study', text: 'Цвет для изучаемых слов', defaultColor: '#5574ff', currentColor: null, index: 2}
      ],
      showModalStatistics: false,
      showModalEditor: false,
      isDataLoaded: false,
      text: '',
      showLoader: true,
      statistics: {}
    }
  },
  async mounted() {
    const id_user = this.$store.getters['auth/getCurrentUser'].id;
    const id_text = this.$route.params.id;
    const responseText = (await axios.post('/text/getById', {id_user, id_text})).data
    this.text = responseText.text

    // const responseStatistics = (await axios.post('/words/getByDictionaryId', ))
    // this.statistics = responseStatistics;

    this.colorPickers.forEach(el => {
      const currentColor = localStorage.getItem('color_' + el.name);
      el.currentColor = currentColor !== null ? currentColor : el.defaultColor;
    })

    this.isDataLoaded = true
    this.showLoader = false
  },
  methods: {
    radioBtnHandle() {
      this.radioBtnBackground = !this.radioBtnBackground;
      this.radioBtnText = !this.radioBtnText;
    },
    setDefaultColors() {
      this.colorPickers.forEach(el => {
        localStorage.setItem('color_' + el.name, el.defaultColor);
        el.currentColor = el.defaultColor;
        el.index += 3;
      });
    },
    async closeModalEditor(updatedText) {
      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      const id_text = this.$route.params.id;

      await axios.post('/text/update', {id_user, id_text, text: updatedText})

      this.text = updatedText

      console.log('closeModalEditor')

      this.showModalEditor = false
    }
  },
  components: {
    RadioButton,
    ColorPicker,
    ModalStatistics,
    ModalEditor,
    Loader
  }
}
</script>

<style scoped lang="scss">
$colorBtnChange: #ffd700;
$colorBtnStatistics: #2fb0ff;
$colorBtnDefault: #bf4bff;

  .main {
    width: 100%;
    color: #fff;
    display: flex;
    flex-direction: row;
    line-height: 35px;
    height: calc(100vh - 70px);

    .leftSide {
      min-width: 350px;
      background-color: #36404A;
      border-right: 2px solid #A2ACBF;
      transition: all .5s;

      .block-change-statistics {
        display: flex;
        align-content: center;
        justify-content: space-evenly;
        align-items: center;
        font-weight: bold;
        font-size: 26px;
        color: #A2ACBF;
        border-bottom: 2px solid #A2ACBF;
        padding: 10px 0;

        .img-document {
          display: flex;

          img {
            width: 23px;
            height: 30px;
            margin-right: 7px;
          }
        }

        .change, .statistics {
          background: transparent;
          padding: 5px 10px;
          border-radius: 3px;
          font-weight: bold;
          font-size: 14px;
          outline: none;

          &:hover {
            color: #fff;
            cursor: pointer;
            transition: all .3s;
          }
        }

        .change {
          border: 1px solid $colorBtnChange;
          color: $colorBtnChange;

          &:hover {
            background-color: $colorBtnChange;
            box-shadow: 0 0 20px $colorBtnChange;
          }
        }

        .statistics {
          border: 1px solid $colorBtnStatistics;
          color: $colorBtnStatistics;

          &:hover {
            background-color: $colorBtnStatistics;
            box-shadow: 0 0 20px $colorBtnStatistics;
          }
        }
      }

      .highlight {
        color: #A2ACBF;
        border-bottom: 2px solid #A2ACBF;
        padding: 10px 0;
        font-weight: bold;
        font-size: 16px;
        text-transform: uppercase;
      }

      .colors {
        font-weight: bold;
        font-size: 18px;
        line-height: 21px;
        padding: 10px 0 0 10px;

        .default {
          background: transparent;
          padding: 5px 10px;
          border-radius: 3px;
          font-weight: bold;
          font-size: 14px;
          border: 1px solid $colorBtnDefault;
          color: $colorBtnDefault;

          &:hover {
            color: #fff;
            cursor: pointer;
            transition: all .3s;
            background-color: $colorBtnDefault;
            box-shadow: 0 0 20px $colorBtnDefault;
          }
        }
      }
    }

    .wrapperLoader {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .rightSide {
      position: relative;
      padding: 10px 0 0 10px;
      width: 100%;
      transition: all .5s;

      .text {
        font-size: 23px;
      }
    }
  }
</style>