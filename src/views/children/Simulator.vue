<template>
  <h1>{{ currentSimulator.name }}</h1>

  <div class="wrapper">
    <div class="messageFewWords" v-if="checkWordsDisplay && checkWords.length < 5">
      Не достаточно слов для изучения, минимум нужно 5, сейчас {{ checkWords.length }}.
    </div>

    <div class="selectWords" v-else-if="checkWordsDisplay">
      <div class="titleSelectDictionary">Выберите словарь</div>
      <Multiselect
        v-model="selectDictionary"
        :options="dictionaries"
        @select="getWords(selectDictionary)"
      />

      <div v-if="words.length !== 0" class="titleSelectWords">Выберите слова для изучения</div>
      <span class="messageSelectMore" v-if="selectWords.length > 0 && selectWords.length < 5">Выбрать нужно больше 5</span>

      <Multiselect
        v-if="words.length !== 0"
        v-model="selectWords"
        :options="words"
        mode="multiple"
      >
        <template v-slot:multiplelabel="{ values }">
          <div class="multiselect-multiple-label">
            {{ values.length }} выбрано
          </div>
        </template>
      </Multiselect>

      <button class="confirm" v-if="selectWords.length >= 5" @click="confirmSelected(selectWords)">Подтвердить</button>
    </div>

    <Loader
        v-if="display && loaded"
    />

    <div v-if="loaded" class="wrapper_two">
      <component
          :is="myComponent"
          :currentWord="currentWord"
          :words="words"
          :currentAnswers="right + wrong"
          @idonotknow="idonotknow"
          @answer="answerHandler"
          @matchHandler="matchHandler">
      </component>

      <Values
          :right="right"
          :wrong="wrong"
          :length="words.length"
      />

    </div>

    <WordBlock
        v-if="words.length >= 5 && loaded"
        :words="words"
    />
  </div>
</template>

<script>
import WordBlock from "@/components/simulators/WordBlock";
import WordTranslate from "@/components/simulators/main/WordTranslate";
import Values from "@/components/simulators/Values";
import Loader from "@/components/app/Loader";
import { shallowRef } from 'vue'
import Multiselect from '@vueform/multiselect'
import axios from "axios";

export default {
  emits: ['idonotknow', 'answer'],
  name: 'simulator',
  data() {
    return {
      currentSimulator: this.$store.getters['simulators/getTitlesOfSimulators'].find(el => el.id === this.$route.params.id),
      currentWord: {},
      tempSentences: [],
      index: 0,
      right: 0,
      wrong: 0,
      myComponent: shallowRef(null),
      display: true,
      loaded: false,
      words: [],
      dictionaries: [],
      selectDictionary: null,
      selectWords: [],
      checkWords: [],
      checkWordsDisplay: false,
    }
  },
  methods: {
    async idonotknow() {
      this.words[this.index].color = '#FF4D4D';
      this.wrong++;

      this.words[this.index].wrong++;
      this.words[this.index].temporal_number_correct = 0;

      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      const data = {
        wrong: this.currentWord.wrong,
        correct: this.currentWord.correct,
        temporal_number_correct: this.currentWord.temporal_number_correct,
        id_user,
        id_word_information: this.currentWord.id_word_information
      };

      this.currentWord = this.words[this.index++ + 1];

      console.log('idonotknow');

      await axios.post('/word/update', data)
    },
    async answerHandler(answer) {
      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      let id;
      if (typeof answer === "object") {
        id = answer.id_word_information;
      } else {
        id = (await axios.post('/word/getIdByWord', { id_user, answer })).data.id_word_information;
      }

      if (this.currentWord.id_word_information === id) {
        this.words[this.index].color = '#19B500';
        this.right++;

        this.words[this.index].correct++;
        this.words[this.index].temporal_number_correct++;
      } else {
        this.words[this.index].color = '#FF4D4D';
        this.wrong++;

        this.words[this.index].wrong++;
        this.words[this.index].temporal_number_correct = 0;
      }

      this.words[this.index].repetitions++;

      const data = {
        correct: this.words[this.index].correct,
        wrong: this.words[this.index].wrong,
        temporal_number_correct: this.words[this.index].temporal_number_correct,
        id_user,
        id_word_information: this.words[this.index].id_word_information,
        repetitions: this.words[this.index].repetitions
      };

      await axios.post('/word/update', data)

      this.currentWord = this.words[this.index++ + 1];
    },
    async matchHandler(bool, element) {
      const id_user = this.$store.getters['auth/getCurrentUser'].id;

      const curWord = this.words.find(el => el.id_word_information === element.id_word_information)

      if (bool) {
        console.log('TRUE')
        curWord.color = '#19B500';
        this.right++;

        curWord.correct++;
        curWord.temporal_number_correct++;
      } else {
        console.log('FALSE')
        curWord.color = '#FF4D4D';
        this.wrong++;
        curWord.wrong++;
        curWord.temporal_number_correct = 0;
      }

      curWord.repetitions++;

      const data = {
        correct: curWord.correct,
        wrong: curWord.wrong,
        temporal_number_correct: curWord.temporal_number_correct,
        id_user,
        id_word_information: curWord.id_word_information,
        repetitions: curWord.repetitions
      };

      console.log('DATA word update', data);

      await axios.post('/word/update', data)
    },
    async getYandex() {
      // const key = "dict.1.1.20210601T151729Z.cb8f7f225a387d74.67724ff1c9c838097dba9aace6250395f85202d2";
      // const url = `https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key=${key}&lang=en-ru&text=time`;
      // const data = await fetch(url);
      // const result = await data.json();
      //
      // console.log('data', result);

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
      // console.log(result);
    },
    async getWords(id_dictionary) {
      this.words = [];

      const id_user = this.$store.getters['auth/getCurrentUser'].id;

      if ('known' === id_dictionary) {
        const responseWords = (await axios.post('/words/getByMeaningWithTranslations', { id_user, meaning: 0 })).data;
        responseWords.map(el => {
          el.value = el.id_word_information
          el.label = el.word
        });

        this.words = responseWords;
      } else if ('study' === id_dictionary) {
        const responseWords = (await axios.post('/words/getByMeaningWithTranslations', { id_user, meaning: 1 })).data;
        responseWords.map(el => {
          el.value = el.id_word_information
          el.label = el.word
        });

        this.words = responseWords;
      } else {
        const responseWords = (await axios.post('/words/getByDictionaryId', { id_user, id_dictionary })).data;
        responseWords.map(el => {
          el.value = el.id_word_information
          el.label = el.word
        });

        this.words = responseWords;
      }
    },
    async confirmSelected(selectedIds) {
      const words = [];
      this.checkWordsDisplay = false;

      for (const selected in selectedIds) {
        if (selectedIds.hasOwnProperty(selected)) {
          words.push(selectedIds[selected]);
        }
      }

      this.words = this.words.filter(el => {
        if (words.indexOf(el.id_word_information) !== -1) {
          return el;
        }
      });

      this.words.map(el => el.color = '#2B2B2B')

      if (this.currentSimulator.component === 'WordTranslate' || this.currentSimulator.component === 'WordMatching') {
        this.words.forEach(el => {
          if (el.correct === null) el.correct = 0;
          if (el.wrong === null) el.wrong = 0;
          if (el.temporal_number_correct === null) el.temporal_number_correct = 0;
          if (el.repetitions === null) el.repetitions = 0;

          el.correct = parseInt(el.correct);
          el.wrong = parseInt(el.wrong);
          el.temporal_number_correct = parseInt(el.temporal_number_correct);
          el.repetitions = parseInt(el.repetitions);
        })
      } else if (this.currentSimulator.component === 'TranslateWord' || this.currentSimulator.component === 'WriteWord') {
        this.words.map(el => {
          if (el.correct === null) el.correct = 0;
          if (el.wrong === null) el.wrong = 0;
          if (el.temporal_number_correct === null) el.temporal_number_correct = 0;
          if (el.repetitions === null) el.repetitions = 0;

          el.tempEl = el.translation;
          el.translation = el.word;
          el.word = el.tempEl;

          el.correct = parseInt(el.correct);
          el.wrong = parseInt(el.wrong);
          el.temporal_number_correct = parseInt(el.temporal_number_correct);
          el.repetitions = parseInt(el.repetitions);
        })
      }

      this.words = this.words.filter(el => el.translation !== null)

      console.log('words', words)
      console.log('this.words AAAAAAAAAAAAAAAAAA', this.words)

      this.currentWord = this.words[this.index];
      this.display = false
      this.loaded = true;
    }
  },
  async mounted() {
    import(`@/components/simulators/main/${this.currentSimulator.component}`).then(component => this.myComponent = component.default);

    const id_user = this.$store.getters['auth/getCurrentUser'].id;
    this.checkWords = (await axios.post('/words/getStudyWords', { id_user })).data;
    this.checkWordsDisplay = true;

    const responseDictionaries = (await axios.post('/dictionary/getAll', { id_user })).data

    responseDictionaries.map(el => {
      el.value = el.id_dictionary
      el.label = el.title
    })

    responseDictionaries.unshift({ value: 'study', label: 'Изучаемые' });
    responseDictionaries.unshift({ value: 'known', label: 'Известные' });

    this.dictionaries = responseDictionaries;

    // WordTranslate
    // TranslateWord
    // WordMatching
    // WriteWord
  },
  components: {
    WordBlock,
    WordTranslate,
    Values,
    Loader,
    Multiselect
  }
}
</script>

<style scoped lang="scss">
@import "~@vueform/multiselect/themes/default.css";

  h1 {
    margin-top: 30px;
    margin-bottom: 20px;
    color: #fff;
  }

  .wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;

    .selectWords {
      padding: 10px 20px;
      background-color: #556676;
      border-radius: 3px;

      .confirm {
        background: transparent;
        border: 2px solid #0DFF92;
        border-radius: 3px;
        font-weight: bold;
        font-size: 18px;
        color: #0DFF92;
        padding: 5px 10px;
        cursor: pointer;
        margin-top: 10px;
        transition: .3s;

        &:hover {
          background: #0DFF92;
          color: #36404A;
        }
      }

      .titleSelectDictionary {
        font-size: 18px;
        font-weight: bold;
        color: #fff;
        margin-bottom: 10px;
      }

      .titleSelectWords {
        font-size: 18px;
        font-weight: bold;
        color: #fff;
        margin-top: 10px;
        margin-bottom: 5px;
      }

      .messageSelectMore {
        font-size: 14px;
        font-weight: bold;
        color: #ff6868;
        margin-bottom: 10px;
        display: block;
      }

      select {
        padding: 5px 10px;
        font-size: 16px;
      }
    }

    .messageFewWords {
      font-size: 25px;
      font-weight: bold;
      color: #fff;
    }

    .wrapper_two {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
    }
  }
</style>