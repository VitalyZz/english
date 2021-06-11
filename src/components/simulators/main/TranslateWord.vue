<template>
  <div class="main">
    <div class="top" v-if="words.length > currentAnswers">
      <div class="wrapper-left-block">
        {{ currentWord.word }}
<!--        <div class="word">{{ currentWord.word }}</div>-->
      </div>
      <div class="translate-block">
        <button class="translation" v-for="word in randomWords" @click="$emit('answer', word)">{{ word.translations }}</button>
        <button class="donotknow" @click="$emit('idonotknow')">Я не знаю</button>
      </div>
    </div>
    <div class="messageEnd" v-else>
      <h1>Конец</h1>
      <router-link to="/simulators">Вернуться к тренажерам</router-link>
      <!--      <router-link :to="'/simulator/' + $route.params.id">Начать заново</router-link>-->
    </div>
    <!--    <div class="bottom">-->
    <!--      <div class="sentences">-->
    <!--        <div class="sentence" v-for="sentence in tempSentences">-->
    <!--          {{ sentence }}-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->
  </div>
</template>

<script>
export default {
  props: ['words', 'currentWord', 'currentAnswers'],
  computed: {
    randomWords() {
      let temp = this.words.slice(0);
      temp = temp.filter(el => el.id_word_information !== this.currentWord.id_word_information)
      temp = temp.slice(0, 3);
      temp.push(this.currentWord)
      temp.sort(function () { return 0.5 - Math.random() });

      console.log('temp', temp);

      return temp;
    }
  }
}
</script>

<style scoped lang="scss">
.main {
  display: flex;
  flex-direction: column;
  background: #2B2B2B;
  box-shadow: 8px 8px 10px rgba(0, 0, 0, 0.7);
  border-radius: 5px;
  padding: 20px 30px;

  .top {
    display: flex;
    justify-content: space-between;

    .wrapper-left-block {
      font-weight: bold;
      font-size: 24px;
      line-height: 28px;
      color: #fff;
      text-transform: uppercase;

      .word {
        //font-weight: bold;
        //font-size: 24px;
        //line-height: 28px;
        //color: #fff;
        //text-transform: uppercase;
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

  .bottom {
    max-width: 700px;
    margin-top: 20px;

    .sentences {
      .sentence {
        color: #FFFFFF;
        font-size: 20px;

        &:not(:last-child) {
          margin-bottom: 5px;
        }
      }
    }
  }

  .translate-block {
    display: flex;
    flex-direction: column;
    margin-left: 50px;

    .translation {
      padding: 10px 70px;
      background: #36404A;
      border-radius: 100px;
      font-size: 18px;
      font-weight: bold;
      color: #fff;
      border: none;
      cursor: pointer;

      &:not(:last-child) {
        margin-bottom: 10px;
      }

      &:hover {
        background: #2f475f;
      }
    }

    .donotknow {
      background: #FF4D4D;
      border-radius: 100px;
      padding: 10px 70px;
      font-size: 22px;
      font-weight: bold;
      margin-top: 30px;
      border: none;
      cursor: pointer;

      &:hover {
        background: #ff6969;
      }
    }
  }
}
</style>