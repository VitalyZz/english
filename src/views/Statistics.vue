<template>
<!--  <h1>Statistics</h1>-->
  <div class="main">
<!--    <div class="firstChart"></div>-->
<!--    <div class="secondChart"></div>-->
    <div class="statistic_text">Количество слов: {{ words }}</div>
    <div class="statistic_text">Количество правильных ответов: {{ correct }}</div>
    <div class="statistic_text">Количество неправильных ответов: {{ wrong }}</div>
    <div class="thirdChart"></div>
  </div>
</template>

<!--<GoogleCharts/>-->

<script>
import axios from "axios";

export default {
  data() {
    return {
      words: 0,
      correct: 0,
      wrong: 0
    }
  },
  async mounted() {
    const id_user = this.$store.getters['auth/getCurrentUser'].id;
    const data = (await axios.post('/words/getAllCountOfWords', {id_user})).data

    this.words = data.words;
    this.correct = data.correct;
    this.wrong = data.wrong;

    // this.start1();
    // this.start2();
    this.start3();
  },
  methods: {
    start1() {
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(this.drawVisualization1);
    },
    start2() {
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(this.drawVisualization2);
    },
    start3() {
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(this.drawVisualization3);
    },
    drawVisualization1() {
      const data = new google.visualization.DataTable();

      data.addColumn('number', 'День');
      data.addColumn('number', 'Верных');
      data.addColumn({ role: 'annotation', type: 'string' });
      data.addColumn('number', 'Не верных');
      data.addColumn({ role: 'annotation', type: 'string' });

      const arr = [
        {day: 1, right: 10, wrong: 4},
        {day: 2, right: 4, wrong: 3},
        {day: 3, right: 7, wrong: 2},
        {day: 4, right: 14, wrong: 1},
        {day: 5, right: 2, wrong: 5}
      ];

      console.log(arr);

      arr.forEach(function(item) {
        data.addRow([item['day'], item['right'], item['right'].toString(), item['wrong'], item['wrong'].toString()]);
      });

      var options = {
        title : `Коды.`,
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
        hAxis: {title: 'Коды',
          textStyle: {color: '#fff'},
          titleTextStyle: {color: '#fff'},
          gridlines: {
            color: '#6E6E6E'
          }
        },
        seriesType: 'bars',
        series: {
          0: {type: 'line', annotations: {alwaysOutside: true} },
          1: {type: 'line', annotations: {alwaysOutside: true} },
        },
        backgroundColor: '#36404A',
        colors: ['#F1C232', '#AF4EF4'],
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

      var chart = new google.visualization.ComboChart(document.querySelector('.firstChart'));
      chart.draw(data, options);
    },
    drawVisualization2() {
      const data = new google.visualization.DataTable();

      data.addColumn('number', 'День');
      data.addColumn('number', 'Верных');
      data.addColumn({ role: 'annotation', type: 'string' });
      data.addColumn('number', 'Не верных');
      data.addColumn({ role: 'annotation', type: 'string' });

      const arr = [
        {day: 1, right: 241, wrong: 34},
        {day: 2, right: 742, wrong: 23},
        {day: 3, right: 234, wrong: 43},
        {day: 4, right: 543, wrong: 72},
        {day: 5, right: 234, wrong: 45}
      ];

      console.log(arr);

      arr.forEach(function(item) {
        data.addRow([item['day'], item['right'], item['right'].toString(), item['wrong'], item['wrong'].toString()]);
      });

      var options = {
        title : `Коды.`,
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
        hAxis: {title: 'Коды',
          textStyle: {color: '#fff'},
          titleTextStyle: {color: '#fff'},
          gridlines: {
            color: '#6e6e6e'
          }
        },
        seriesType: 'bars',
        series: {
          0: {type: 'line', annotations: {alwaysOutside: true} },
          1: {type: 'line', annotations: {alwaysOutside: true} },
        },
        backgroundColor: '#36404A',
        colors: ['#F1C232', '#AF4EF4'],
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

      var chart = new google.visualization.ComboChart(document.querySelector('.secondChart'));
      chart.draw(data, options);
    },
    async drawVisualization3() {

      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      const resultKnown = (await axios.post('/words/getByMeaning', { id_user, meaning: 0 })).data;
      const resultStudy = (await axios.post('/words/getByMeaning', { id_user, meaning: 1 })).data;

      const data = new google.visualization.DataTable();

      data.addColumn('string', 'name');
      data.addColumn('number', 'count');
      data.addColumn({ role: 'annotation', type: 'string' });

      const arr = [
        {name: 'Процент известных слов', count: resultKnown.length},
        {name: 'Процент изучаемых слов', count: resultStudy.length},
      ];

      arr.forEach(function(item) {
        data.addRow([item['name'], item['count'], item['count'].toString()]);
      });

      var options = {
        title : `Соотношение двух типов слов.`,
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
        hAxis: {title: 'Коды',
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
          0: { color: '#40ff3a' },
          1: { color: '#3b91ff' },
        },
        backgroundColor: '#36404A',
        // colors: ['#F1C232', '#AF4EF4'],
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

      var chart = new google.visualization.PieChart(document.querySelector('.thirdChart'));
      chart.draw(data, options);
    }
  },
  components: {

  }
}
</script>

<style scoped lang="scss">
  h1 {
    text-align: center;
    color: #fff;
    margin-top: 60px;
  }

  .main {
    margin-top: 60px;

    .statistic_text {
      font-size: 18px;
      font-weight: bold;
      color: #fff;
    }

    .firstChart {
      width: 100%;
      height: 500px;
    }

    .secondChart {
      width: 100%;
      height: 500px;
    }

    .thirdChart {
      width: 100%;
      height: 500px;
    }
  }
</style>