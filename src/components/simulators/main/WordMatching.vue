<template>
  <div class="main">
    <div class="top" v-if="words.length > currentAnswers">
      <div class="right-block">
        <button class="translate" v-for="el in words" @click="wordHandler(el)" :style="{backgroundColor: el.colorWord}">{{ el.word }}</button>
      </div>
      <div class="left-block">
        <button class="translate" v-for="el in tempArrayOfWords" @click="translationHandler(el)" :style="{backgroundColor: el.colorTranslation}">{{ el.translation }}</button>
      </div>
    </div>
    <div class="messageEnd" v-else>
      <h1>Конец</h1>
      <router-link to="/simulators">Вернуться к тренажерам</router-link>
    </div>
  </div>
</template>

<script>
export default {
  emits: ['matchHandler'],
  props: ['tempCurrentWord', 'tempCurrentBlock', 'right', 'wrong', 'words', 'currentAnswers'],
  data() {
    return {
      currentWord: null,
      currentTranslation: null,
      tempArrayOfWords: null
    }
  },
  mounted() {
    this.words.map(el => {
      el.selectedWord = false
      el.selectedTranslation = false
      el.disabledWord = false
      el.disabledTranslation = false
      el.colorWord = '#36404A'
      el.colorTranslation = '#36404A'
    });

    this.tempArrayOfWords = this.words.slice(0);
    this.tempArrayOfWords = this.tempArrayOfWords.sort(() => Math.random() - 0.5)

    console.log('this.words', this.words)
    console.log('this.tempArrayOfWords', this.tempArrayOfWords)
  },
  methods: {
    wordHandler(element) {
      if (!element.disabledWord) {
        this.currentWord = element;
        this.currentWord.selectedWord = true;

        this.words.map(el => el.colorWord = !el.disabledWord ? '#36404A' : el.colorWord);
        console.log(this.words)

        const checkOne = this.words.find(el => el.selectedWord === true);
        const checkTwo = this.words.find(el => el.selectedTranslation === true);

        if (checkOne && checkTwo) {
          this.check(checkOne, checkTwo)
        } else {
          this.currentWord.colorWord = '#9cffff';
        }
      }
    },
    translationHandler(element) {
      if (!element.disabledTranslation) {
        this.currentTranslation = element;
        this.currentTranslation.selectedTranslation = true;

        this.words.map(el => el.colorTranslation = !el.disabledTranslation ? '#36404A' : el.colorTranslation);
        console.log(this.words)

        const checkOne = this.words.find(el => el.selectedWord === true);
        const checkTwo = this.words.find(el => el.selectedTranslation === true);

        if (checkOne && checkTwo) {
          this.check(checkOne, checkTwo)
        } else {
          this.currentTranslation.colorTranslation = '#9cffff';
        }
      }
    },
    check(checkOne, checkTwo) {
      console.log('check')

      console.log('checkOne', checkOne)
      console.log('checkOne', checkTwo)

      checkOne.disabledWord = true;

      checkOne.selectedWord = false;
      checkTwo.selectedTranslation = false;


      if (checkOne.id_word_information === checkTwo.id_word_information) {
        this.currentWord.colorWord = '#1bbd00'
        this.currentTranslation.colorTranslation = '#1bbd00'
        this.$emit('matchHandler', true, this.currentWord)
        checkTwo.disabledTranslation = true;

        if (!this.words.find(el => el.disabledWord === false)) {
          console.log('PP', this.words)
          this.words.map(el => el.disabledTranslation = true);
          this.words.map(el => {
            if (el.colorTranslation !== '#1bbd00') {
              el.colorTranslation = '#ff4d4d'
            }
          });
        }
      } else {
        if (this.words.find(el => el.disabledWord === false)) {
          this.currentTranslation.colorTranslation = '#ff4d4d'
          setTimeout(() => {
            this.currentTranslation.colorTranslation = '#36404A'
          }, 300)
        } else {
          console.log('PP', this.words)
          this.words.map(el => el.disabledTranslation = true);
          this.words.map(el => {
            if (el.colorTranslation !== '#1bbd00') {
              el.colorTranslation = '#ff4d4d'
            }
          });
        }

        this.currentWord.colorWord = '#ff4d4d'
        this.$emit('matchHandler', false, this.currentWord)
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .main {
    display: flex;
    background: #2B2B2B;
    box-shadow: 8px 8px 10px rgba(0, 0, 0, 0.7);
    border-radius: 5px;
    padding: 20px 30px;

    .top {
      display: flex;
      justify-content: space-between;

      .left-block, .right-block {
        display: flex;
        flex-direction: column;
        margin-left: 50px;

        .translate {
          padding: 10px 70px;
          border-radius: 100px;
          font-size: 22px;
          font-weight: bold;
          color: #fff;
          border: none;
          cursor: pointer;

          &:not(:last-child) {
            margin-bottom: 10px;
          }
        }
      }
    }

    .messageEnd {
      display: flex;
      flex-direction: column;
      align-items: center;

      h1 {
        color: #0DFF92;
      }

      a {
        margin-top: 10px;
        color: #ffffff;

        &:hover {
          color: #0DFF92;
        }
      }
    }

    .right-block {
      .translate {
        background: #36404A;
        transition: .3s;

        &:hover {
          background: #2f475f;
        }
      }
    }

    .left-block, {
      .translate {
        background: #36404A;
        transition: .3s;

        &:hover {
          background: #2f475f;
        }
      }
    }
  }
</style>