<template>
  <div class="main">
    <div v-if="showLoader" class="wrapperLoader">
      <Loader/>
    </div>
    <div class="statistic_text" v-if="!showLoader">Количество слов: {{ words }}</div>
    <div class="statistic_text" v-if="!showLoader">Количество правильных ответов: {{ correct }}</div>
    <div class="statistic_text" v-if="!showLoader">Количество неправильных ответов: {{ wrong }}</div>
    <div class="firstChart"></div>
  </div>
</template>

<!--<GoogleCharts/>-->

<script>
import Loader from "@/components/app/Loader";
import axios from "axios";

export default {
  data() {
    return {
      words: 0,
      correct: 0,
      wrong: 0,
      showLoader: true
    }
  },
  async mounted() {
    const id_user = this.$store.getters['auth/getCurrentUser'].id;
    const data = (await axios.post('/words/getAllCountOfWords', {id_user})).data

    this.words = isNaN(parseInt(data.words)) ? 0 : parseInt(data.words);
    this.correct = isNaN(parseInt(data.correct)) ? 0 : parseInt(data.correct);
    this.wrong = isNaN(parseInt(data.wrong)) ? 0 : parseInt(data.wrong);

    if (this.words !== 0) {
      this.start1();
    }

    this.showLoader = false;
  },
  methods: {
    start1() {
      google.charts.load('current', {'packages': ['corechart']});
      google.charts.setOnLoadCallback(this.drawVisualization1);
    },
    async drawVisualization1() {
      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      const resultKnown = (await axios.post('/words/getByMeaning', {id_user, meaning: 0})).data;
      const resultStudy = (await axios.post('/words/getByMeaning', {id_user, meaning: 1})).data;

      const data = new google.visualization.DataTable();

      data.addColumn('string', 'name');
      data.addColumn('number', 'count');
      data.addColumn({role: 'annotation', type: 'string'});

      const arr = [
        {name: 'Процент известных слов', count: resultKnown.length},
        {name: 'Процент изучаемых слов', count: resultStudy.length},
      ];

      arr.forEach(function (item) {
        data.addRow([item['name'], item['count'], item['count'].toString()]);
      });

      var options = {
        title: `Соотношение двух типов слов.`,
        vAxis: {
          title: 'Количество (шт)',
          format: 'decimal',
          textStyle: {color: '#fff'},
          titleTextStyle: {color: '#fff'},
          gridlines: {
            color: '#6e6e6e'
          },
          baselineColor: 'transparent'
        },
        titleTextStyle: {
          color: '#fff'
        },
        hAxis: {
          title: 'Коды',
          textStyle: {color: '#fff'},
          titleTextStyle: {color: '#fff'},
          gridlines: {
            color: '#6e6e6e'
          }
        },
        pieSliceTextStyle: {
          color: 'black',
          fontSize: 18,
        },
        slices: {
          0: {color: '#40ff3a'},
          1: {color: '#3b91ff'},
        },
        backgroundColor: '#36404A',
        isStacked: true,
        legend: {position: 'top', textStyle: {color: '#fff'}},
        tooltip: {trigger: 'none'},
        annotations: {
          textStyle: {
            bold: true,
            fontSize: 16
          },
          alwaysOutside: true
        }
      };

      try {
        const chart = new google.visualization.PieChart(document.querySelector('.firstChart'));
        chart.draw(data, options);
      } catch (e) {
        console.log('Statistics page:', e.message)
      }
    }
  },
  components: {
    Loader
  }
}
</script>

<style scoped lang="scss">
.main {
  margin-top: 60px;

  .wrapperLoader {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .statistic_text {
    font-size: 18px;
    font-weight: bold;
    color: #fff;
  }

  .firstChart {
    width: 100%;
    height: 500px;
  }
}
</style>