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

      <div class="highlight" v-if="isDataLoaded">
        <RadioButton :isHighlight="radioBtnText" @click="radioBtnHandle">Выделять текст</RadioButton>
        <RadioButton :isHighlight="radioBtnBackground" @click="radioBtnHandle">Выделять задний фон текста</RadioButton>
      </div>

      <div class="colors" v-if="isDataLoaded">
        <ColorPicker
          v-for="picker in colorPickers"
          :key="picker.index"
          :name="picker.name"
          :color="picker.currentColor"
        >
          {{ picker.text }}
        </ColorPicker>
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
      <p class="text">
        <Word v-for="item in items" :key="item.id_text" :word="item" @wordHandler="wordHandler" :highlight="whatIsHighlight"/>
      </p>
    </div>
  </div>

  <teleport to="body">
    <ModalChangeMeaning
      v-if="isWordClick"
      :x="x"
      :y="y"
      @modalMenuHandler="modalMenuHandler"
    />
  </teleport>

  <teleport to="body">
    <ModalStatistics
        v-if="showModalStatistics"
        @closeModalStatistics="showModalStatistics = false"
        :id_text="$route.params.id"
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
import Word from "@/components/texts/Word";
import ModalChangeMeaning from "@/components/texts/ModalChangeMeaning";
import img_document from '@/assets/images/document.svg';
import axios from "axios";
import {mapGetters, mapMutations} from "vuex";

export default {
  data() {
    return {
      img_document,
      radioBtnText: false,
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
      items: [],
      isWordClick: false,
      x: 0,
      y: 0,
      currentWord: {}
    }
  },
  computed: {
    ...mapGetters('colors', ['getColorGetter']),
    changeColorsKnown() {
      return this.$store.state['colors'].colors.known;
    },
    changeColorsStudy() {
      return this.$store.state['colors'].colors.study;
    },
    changeColorsUnknown() {
      return this.$store.state['colors'].colors.unknown;
    },
    whatIsHighlight() {
      return this.radioBtnText ? 'color' : 'backgroundColor'
    }
  },
  watch: {
    changeColorsKnown() {
      this.items.filter(el => {
        if (el.class === 'known') el.color = this.getColorGetter('known');
      });
    },
    changeColorsStudy() {
      this.items.filter(el => {
        if (el.class === 'study') el.color = this.getColorGetter('study');
      });
    },
    changeColorsUnknown() {
      this.items.filter(el => {
        if (el.class === 'unknown') el.color = this.getColorGetter('unknown');
      });
    }
  },
  async mounted() {
    const id_user = this.$store.getters['auth/getCurrentUser'].id;
    const id_text = this.$route.params.id;
    const responseText = (await axios.post('/text/getById', {id_user, id_text})).data
    this.text = responseText.text

    this.colorPickers.forEach(el => {
      const currentColor = localStorage.getItem('color_' + el.name);
      this.$store.state['colors'].colors[el.name] = currentColor;
      el.currentColor = currentColor !== null ? currentColor : el.defaultColor;
    })

    const highlight = localStorage.getItem('highlight');

    if (highlight !== null && highlight === 'background') {
      this.radioBtnText = false
      this.radioBtnBackground = true
    } else {
      this.radioBtnText = true
      this.radioBtnBackground = false
    }

    await this.algorithm();

    this.isDataLoaded = true
    this.showLoader = false
  },
  methods: {
    ...mapMutations('colors', ['setColor']),
    async modalMenuHandler(meaning) {
      const info = {
        'known': 0,
        'study': 1,
        'unknown': 2,
      };

      const getClass = {
        0: 'known',
        1: 'study',
        2: 'unknown',
      };

      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      const id_word = this.currentWord.id_word;
      const word = this.currentWord.name;

      this.items.filter(el => {
        if (el.name === word) {
          el.class = getClass[info[meaning]];
          el.color = this.getColor(getClass[info[meaning]]);
        }
      });

      this.isWordClick = false;

      if (info[meaning] === 2) {
        await axios.post('/word/deleteWithoutDictionary', {id_user, id_word })
      } else {
        // get yandex START

        let translations = [];

        if (id_word === null) {
          const key = "dict.1.1.20210601T151729Z.cb8f7f225a387d74.67724ff1c9c838097dba9aace6250395f85202d2";

          const url = `https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key=${key}&lang=en-ru&text=${word}`;
          const data = await fetch(url);
          const result = await data.json();

          result.def.forEach(el => {
            el.tr.forEach(el2 => {
              translations.push(el2.text)
            })
          })

          translations = translations.slice(0, 4);

          console.log('translations', translations);

          for (const translation of translations) {
            await axios.post('/words/insertTranslation', {translation, id_word: el.id_word})
          }
        }

        // get yandex END

        await axios.post('/word/changeMeaning', { meaning: info[meaning], id_user, id_word, word, translations })
      }
    },
    wordHandler(word, event) {
      this.isWordClick = true;
      this.currentWord = word;

      const values = event.target.getBoundingClientRect();

      this.x = values.left;
      this.y = values.top + values.height + 5;
    },
    async algorithm() {
      let text = this.text; // здесь считывается весь текст

      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      const resultKnown = (await axios.post('/words/getByMeaning', { id_user, meaning: 0 })).data;
      const resultStudy = (await axios.post('/words/getByMeaning', { id_user, meaning: 1 })).data;

      const arrOfWordsKnown = resultKnown.map(el => el.word);
      const arrOfWordsStudy = resultStudy.map(el => el.word);

      console.log('arrOfWordsKnown', arrOfWordsKnown);
      console.log('arrOfWordsStudy', arrOfWordsStudy);

      // Переменные для дальнейшей работы в while
      let items = [];
      let textMarch;
      let word;
      let end;
      let nameClass;
      let color;

      let id_word = 0;

      const colors = {
        known: this.getColor('known'),
        study: this.getColor('study'),
        unknown: this.getColor('unknown')
      }

      // Заносим все слова в спаны
      while (true) {
        textMarch = text.match(/[a-z]+/i); // проверяме есть ли совпадение
        if (textMarch === null) { // если конец выходим
          break;
        }
        word = textMarch[0]; // берем найденное слово

        if (arrOfWordsKnown.indexOf(word.toLowerCase()) !== -1) {
          nameClass = 'known';
          color = colors.known;
          id_word = resultKnown.find(el => el.word === word.toLowerCase()).id_word
        } // проверям является ли слово известным

        else if (arrOfWordsStudy.indexOf(word.toLowerCase()) !== -1) {
          nameClass = 'study';
          color = colors.study;
          id_word = resultStudy.find(el => el.word === word.toLowerCase()).id_word
        } // проверям является ли слово изучаемым
        else {
          nameClass = 'unknown';
          color = colors.unknown;
          id_word = null;
        } // иначе слово неизвестное

        end = text.split(word); // получаем 2 массива того что лежит по бокам слова

        items.push({
          before: end[0],
          name: word.toLowerCase(),
          text: word,
          class: nameClass,
          id_word,
          color
        })

        text = text.substr(text.indexOf(word) + word.length);
      }

      this.items = items;
    },
    radioBtnHandle() {
      this.radioBtnText = !this.radioBtnText;
      this.radioBtnBackground = !this.radioBtnBackground;

      localStorage.setItem('highlight', this.radioBtnText ? 'color' : 'background')
    },
    setDefaultColors() {
      this.colorPickers.forEach(el => {
        localStorage.setItem('color_' + el.name, el.defaultColor);

        console.log(el.name, el.defaultColor);

        this.setColor({name: el.name, color: el.defaultColor})
        el.currentColor = el.defaultColor;
        el.index += 3;
      });
    },
    getColor(name) {
      return this.getColorGetter(name);
    },
    async closeModalEditor(updatedText) {
      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      const id_text = this.$route.params.id;

      await axios.post('/text/update', {id_user, id_text, text: updatedText})

      this.text = updatedText
      this.showModalEditor = false

      await this.algorithm();
    }
  },
  components: {
    RadioButton,
    ColorPicker,
    ModalStatistics,
    ModalEditor,
    Loader,
    Word,
    ModalChangeMeaning
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