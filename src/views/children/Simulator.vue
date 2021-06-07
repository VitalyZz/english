<template>
  <h1>{{ currentSimulator.name }}</h1>

  <div class="wrapper">
    <WordBlock
      :words="tempWords"
    />

    <Loader
      v-if="display"
    />

    <component
      v-else
      :is="myComponent"
      :tempCurrentWord="tempCurrentWord"
      :tempSentences="tempSentences"
      :tempCurrentBlock="tempCurrentBlock"
      :right="right"
      :wrong="wrong"
      :tempWords="tempWords"
      @idonotknow="changeData"
      @answer="changeData2">
    </component>

    <Values
      :right="right"
      :wrong="wrong"
    />
  </div>
</template>

<script>
import WordBlock from "@/components/simulators/WordBlock";
import WordTranslate from "@/components/simulators/main/WordTranslate";
import Values from "@/components/simulators/Values";
import Loader from "@/components/app/Loader";
import { shallowRef } from 'vue'

export default {
  emits: ['idonotknow', 'answer'],
  name: 'simulator',
  data() {
    return {
      currentSimulator: this.$store.getters['simulators/getTitlesOfSimulators'].find(el => el.id === this.$route.params.id),
      tempWords: [],
      tempCurrentWord: '',
      tempSentences: [],
      tempCurrentBlock: {},
      index: 0,
      right: 0,
      wrong: 0,
      myComponent: shallowRef(null),
      display: true,
    }
  },
  methods: {
    async getSentences(word) {
      // this.display = false;
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
    changeTempCurrentWordAndBlock() {
      this.tempCurrentWord = this.tempWords[this.index].word
      this.tempCurrentBlock = this.tempWords.find(el => el.word === this.tempCurrentWord)
    },
    changeData() {
      this.tempWords[this.index].color = '#FF4D4D';
      this.changeTempCurrentWordAndBlock(this.index++);
      this.wrong++;
      this.getSentences(this.tempWords[this.index].word)
    },
    changeData2(translate) {
      this.tempWords[this.index].color = '#19B500';
      this.changeTempCurrentWordAndBlock(this.index++);
      this.right++;
      this.getSentences(this.tempWords[this.index].word)
    },
    async getYandex() {
      // const key = "dict.1.1.20210601T151729Z.cb8f7f225a387d74.67724ff1c9c838097dba9aace6250395f85202d2";
      // const url = `https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key=${key}&lang=en-ru&text=time`;
      // const data = await fetch(url);
      // const result = await data.json();
      //
      // console.log('data', result);

      const word = 'Conquered'
      const url = `https://owlbot.info/api/v4/dictionary/${word}`;
      const token = "073b418a9db1fcde565807b88a9f2ad25a30cd25";

      const params = {
        method: 'GET',
        headers: {
          'Authorization': 'Token ' + token
        }
      }
      const data = await fetch(url, params);
      const result = await data.json();

      console.log(result);
    }
  },
  mounted() {
    import(`@/components/simulators/main/${this.currentSimulator.component}`).then(component => this.myComponent = component.default);

    if (this.currentSimulator.component === 'WordTranslate') {
      this.tempWords = [
        {word: 'table', translate: ['Слово1', 'Слово2', 'Слово3', 'Слово4'], color: '#2B2B2B'},
        {word: 'chalk', translate: ['Слово5', 'Слово6', 'Слово7', 'Слово8'], color: '#2B2B2B'},
        {word: 'plan', translate: ['Слово8', 'Слово9', 'Слово10', 'Слово11'], color: '#2B2B2B'},
        {word: 'poised', translate: ['Слово12', 'Слово13', 'Слово14', 'Слово15'], color: '#2B2B2B'},
        {word: 'quartz', translate: ['Слово16', 'Слово17', 'Слово18', 'Слово19'], color: '#2B2B2B'},
        {word: 'six', translate: ['Слово20', 'Слово21', 'Слово22', 'Слово23'], color: '#2B2B2B'},
        {word: 'hover', translate: ['Слово24', 'Слово25', 'Слово26', 'Слово27'], color: '#2B2B2B'},
        {word: 'boast', translate: ['Слово28', 'Слово29', 'Слово30', 'Слово31'], color: '#2B2B2B'},
        {word: 'letters', translate: ['Слово32', 'Слово33', 'Слово34', 'Слово35'], color: '#2B2B2B'},
        {word: 'outgoing', translate: ['Слово36', 'Слово37', 'Слово38', 'Слово39'], color: '#2B2B2B'},
        {word: 'desire', translate: ['Слово40', 'Слово41', 'Слово42', 'Слово43'], color: '#2B2B2B'},
        {word: 'sisters', translate: ['Слово44', 'Слово45', 'Слово46', 'Слово47'], color: '#2B2B2B'}
      ]
    } else if (this.currentSimulator.component === 'TranslateWord') {
      this.tempWords = [
        {word: 'Стол', translate: ['Word1', 'Word2', 'Word3', 'Word4'], color: '#2B2B2B'},
        {word: 'мел', translate: ['Word5', 'Word6', 'Word7', 'Word8'], color: '#2B2B2B'},
        {word: 'план', translate: ['Word8', 'Word9', 'Word10', 'Word11'], color: '#2B2B2B'},
        {word: 'сдержанный', translate: ['Word12', 'Word13', 'Word14', 'Word15'], color: '#2B2B2B'},
        {word: 'кварц', translate: ['Word16', 'Word17', 'Word18', 'Word19'], color: '#2B2B2B'},
        {word: 'шесть', translate: ['Word20', 'Word21', 'Word22', 'Word23'], color: '#2B2B2B'},
        {word: 'парить', translate: ['Word24', 'Word25', 'Word26', 'Word27'], color: '#2B2B2B'},
        {word: 'хвастаться', translate: ['Word28', 'Word29', 'Word30', 'Word31'], color: '#2B2B2B'},
        {word: 'письма', translate: ['Word32', 'Word33', 'Word34', 'Word35'], color: '#2B2B2B'},
        {word: 'исходящий', translate: ['Word36', 'Word37', 'Word38', 'Word39'], color: '#2B2B2B'},
        {word: 'желание', translate: ['Word40', 'Word41', 'Word42', 'Word43'], color: '#2B2B2B'},
        {word: 'сестры', translate: ['Word44', 'Word45', 'Word46', 'Word47'], color: '#2B2B2B'}
      ];
    } else if (this.currentSimulator.component === 'WordMatching') {
      this.tempWords = [
        {word: 'table', translate: 'Слово1', color: '#2B2B2B'},
        {word: 'chalk', translate: 'Слово2', color: '#2B2B2B'},
        {word: 'plan', translate: 'Слово3', color: '#2B2B2B'},
        {word: 'poised', translate: 'Слово4', color: '#2B2B2B'},
        {word: 'quartz', translate: 'Слово5', color: '#2B2B2B'},
        {word: 'six', translate: 'Слово6', color: '#2B2B2B'},
        {word: 'hover', translate: 'Слово7', color: '#2B2B2B'},
        {word: 'boast', translate: 'Слово8', color: '#2B2B2B'},
        {word: 'letters', translate: 'Слово9', color: '#2B2B2B'},
        {word: 'outgoing', translate: 'Слово10', color: '#2B2B2B'},
        {word: 'desire', translate: 'Слово11', color: '#2B2B2B'},
        {word: 'sisters', translate: 'Слово12', color: '#2B2B2B'}
      ]
    } else if (this.currentSimulator.component === 'WriteWord') {
      this.tempWords = [
        {word: 'table', translate: ['Слово1', 'Слово2', 'Слово3', 'Слово4'], color: '#2B2B2B'},
        {word: 'chalk', translate: ['Слово5', 'Слово6', 'Слово7', 'Слово8'], color: '#2B2B2B'},
        {word: 'plan', translate: ['Слово8', 'Слово9', 'Слово10', 'Слово11'], color: '#2B2B2B'},
        {word: 'poised', translate: ['Слово12', 'Слово13', 'Слово14', 'Слово15'], color: '#2B2B2B'},
        {word: 'quartz', translate: ['Слово16', 'Слово17', 'Слово18', 'Слово19'], color: '#2B2B2B'},
        {word: 'six', translate: ['Слово20', 'Слово21', 'Слово22', 'Слово23'], color: '#2B2B2B'},
        {word: 'hover', translate: ['Слово24', 'Слово25', 'Слово26', 'Слово27'], color: '#2B2B2B'},
        {word: 'boast', translate: ['Слово28', 'Слово29', 'Слово30', 'Слово31'], color: '#2B2B2B'},
        {word: 'letters', translate: ['Слово32', 'Слово33', 'Слово34', 'Слово35'], color: '#2B2B2B'},
        {word: 'outgoing', translate: ['Слово36', 'Слово37', 'Слово38', 'Слово39'], color: '#2B2B2B'},
        {word: 'desire', translate: ['Слово40', 'Слово41', 'Слово42', 'Слово43'], color: '#2B2B2B'},
        {word: 'sisters', translate: ['Слово44', 'Слово45', 'Слово46', 'Слово47'], color: '#2B2B2B'}
      ];
    } else if (this.currentSimulator.component === 'WriteTheWordInContext') {
      this.tempWords = [
        {word: 'table', translate: ['Слово1', 'Слово2', 'Слово3', 'Слово4'], color: '#2B2B2B'},
        {word: 'chalk', translate: ['Слово5', 'Слово6', 'Слово7', 'Слово8'], color: '#2B2B2B'},
        {word: 'plan', translate: ['Слово8', 'Слово9', 'Слово10', 'Слово11'], color: '#2B2B2B'},
        {word: 'poised', translate: ['Слово12', 'Слово13', 'Слово14', 'Слово15'], color: '#2B2B2B'},
        {word: 'quartz', translate: ['Слово16', 'Слово17', 'Слово18', 'Слово19'], color: '#2B2B2B'},
        {word: 'six', translate: ['Слово20', 'Слово21', 'Слово22', 'Слово23'], color: '#2B2B2B'},
        {word: 'hover', translate: ['Слово24', 'Слово25', 'Слово26', 'Слово27'], color: '#2B2B2B'},
        {word: 'boast', translate: ['Слово28', 'Слово29', 'Слово30', 'Слово31'], color: '#2B2B2B'},
        {word: 'letters', translate: ['Слово32', 'Слово33', 'Слово34', 'Слово35'], color: '#2B2B2B'},
        {word: 'outgoing', translate: ['Слово36', 'Слово37', 'Слово38', 'Слово39'], color: '#2B2B2B'},
        {word: 'desire', translate: ['Слово40', 'Слово41', 'Слово42', 'Слово43'], color: '#2B2B2B'},
        {word: 'sisters', translate: ['Слово44', 'Слово45', 'Слово46', 'Слово47'], color: '#2B2B2B'}
      ];
    }

    this.changeTempCurrentWordAndBlock();
    this.getSentences(this.tempCurrentWord);

    this.getYandex();
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
    align-items: center;
    justify-content: space-between;
  }
</style>