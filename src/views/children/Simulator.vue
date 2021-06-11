<template>
  <h1>{{ currentSimulator.name }}</h1>

  <div class="wrapper">
    <Loader
        v-if="display"
    />

    <div v-else class="wrapper_two">
      <component
          :is="myComponent"
          :currentWord="currentWord"
          :words="words"
          :currentAnswers="right + wrong"
          @idonotknow="idonotknow"
          @answer="answerHandler">
      </component>

      <Values
          :right="right"
          :wrong="wrong"
          :length="words.length"
      />

    </div>

    <WordBlock
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
      words: []
    }
  },
  methods: {
    async getSentences(word) {
      let language = '';

      if (this.currentSimulator.component === 'TranslateWord') {
        language = 'ru'
      } else {
        language = 'en_US'
      }

      try {
        const response = await fetch(`https://api.dictionaryapi.dev/api/v2/entries/${language}/${word}`)
        const json = await response.json();
        const data = json[0].meanings.map(el => el.definitions.map(el2 => el2.example));
        const examples = [];
        data.map(el => el.forEach(el2 => examples.push(el2)));
        this.tempSentences = examples.slice(0, 3);
      } catch (e) {
        this.tempSentences = ['Sentences is not exist'];
      }

      this.display = false;

      // console.log('examples', examples.slice(0, 3));
    },
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

      // this.getSentences(this.words[this.index].word)
    },
    async answerHandler(answer) {
      if (this.currentWord.id_word_information === answer.id_word_information) {
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

      const id_user = this.$store.getters['auth/getCurrentUser'].id;

      const data = {
        correct: this.words[this.index].correct,
        wrong: this.words[this.index].wrong,
        temporal_number_correct: this.words[this.index].temporal_number_correct,
        id_user,
        id_word_information: this.words[this.index].id_word_information
      };

      console.log('CORRECT', this.words[this.index].correct)
      console.log('answerHandler');

      await axios.post('/word/update', data)

      this.currentWord = this.words[this.index++ + 1];

      // this.getSentences(this.words[this.index].word)
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
    }
  },
  async mounted() {
    import(`@/components/simulators/main/${this.currentSimulator.component}`).then(component => this.myComponent = component.default);

    const id_user = this.$store.getters['auth/getCurrentUser'].id;

    // WordTranslate
    // TranslateWord
    // WordMatching
    // WriteWord
    // WriteTheWordInContext

    if (this.currentSimulator.component === 'WordTranslate') {
      const words = (await axios.post('/words/getStudyWords', { id_user })).data
      words.map(el => el.color = '#2B2B2B')

      words.forEach(el => {
        if (el.correct === null) el.correct = 0;
        if (el.wrong === null) el.wrong = 0;
        if (el.temporal_number_correct === null) el.temporal_number_correct = 0;

        el.correct = parseInt(el.correct);
        el.wrong = parseInt(el.wrong);
        el.temporal_number_correct = parseInt(el.temporal_number_correct);
      })
      
      this.currentWord = words[this.index];
      this.words = words;
      
      console.log('words', words);
    } else if (this.currentSimulator.component === 'TranslateWord') {
      const words = (await axios.post('/words/getStudyWords', { id_user })).data
      words.map(el => el.color = '#2B2B2B')

      words.map(el => {
        if (el.correct === null) el.correct = 0;
        if (el.wrong === null) el.wrong = 0;
        if (el.temporal_number_correct === null) el.temporal_number_correct = 0;

        el.tempEl = el.translations;
        el.translations = el.word;
        el.word = el.tempEl;

        el.correct = parseInt(el.correct);
        el.wrong = parseInt(el.wrong);
        el.temporal_number_correct = parseInt(el.temporal_number_correct);
      })

      this.currentWord = words[this.index];
      this.words = words;

      console.log('words', words);
    }  else if (this.currentSimulator.component === 'WriteWord') {
      const words = (await axios.post('/words/getStudyWords', { id_user })).data
      words.map(el => el.color = '#2B2B2B')

      words.map(el => {
        if (el.correct === null) el.correct = 0;
        if (el.wrong === null) el.wrong = 0;
        if (el.temporal_number_correct === null) el.temporal_number_correct = 0;

        el.tempEl = el.translations;
        el.translations = el.word;
        el.word = el.tempEl;

        el.correct = parseInt(el.correct);
        el.wrong = parseInt(el.wrong);
        el.temporal_number_correct = parseInt(el.temporal_number_correct);
      })

      this.currentWord = words[this.index];
      this.words = words;

      console.log('words', words);
    }
    
    // await this.getSentences(this.currentWord);
    // await this.getYandex();

    this.display = false
  },
  components: {
    WordBlock,
    WordTranslate,
    Values,
    Loader
  }
}
</script>

<style scoped lang="scss">
  h1 {
    margin-top: 30px;
    margin-bottom: 20px;
    color: #fff;
  }

  .wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;

    .wrapper_two {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
    }
  }
</style>