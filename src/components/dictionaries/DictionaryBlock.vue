<template>
  <div class="dictionary">
    <div class="title" :style="{backgroundColor: color }">
      <router-link :to="`/dictionary/${id}`">{{ title }}</router-link>
    </div>
    <div class="main">
      <!--        <div class="word">Lorem</div>-->
      <!--        <div class="word">ipsumFFW</div>-->
      <!--        <div class="word">dolor</div>-->
      <!--        <div class="word">consectetur</div>-->
      <!--        <div class="word">adipisicing</div>-->
    </div>
    <div class="statistics" :style="{backgroundColor: color }">
<!--      <div class="number">31 / 70</div>-->
<!--      <div class="percents">44.29%</div>-->
    </div>
    <div class="buttons">
      <button class="btn-statistics" @click="showModalStatistics = true">Статистика</button>
      <button class="btn-delete" v-if="isDelete" @click="deleteDictionary">Удалить</button>
    </div>
  </div>

  <teleport to="body">
    <DictionaryModalStatistics
      v-if="showModalStatistics"
      @closeModalStatistics="showModalStatistics = false"
      :id_dictionary="id"
    />
  </teleport>
</template>

<script>
import axios from "axios";
import DictionaryModalStatistics from '@/components/dictionaries/DictionaryModalStatistics'

export default {
  data() {
    return {
      showModalStatistics: false
    }
  },
  props: {
    isDelete: { type: Boolean, default: true, required: false },
    color: { type: String, default: '#21a4ff', required: false },
    title: { type: String, required: true },
    id: { type: [String, Number], required: true },
  },
  methods: {
    async deleteDictionary() {
      const id_user = this.$store.getters['auth/getCurrentUser'].id;
      const data = { id_user, id_dictionary: this.id}
      await axios.post('/dictionary/delete', data);
    }
  },
  components: {
    DictionaryModalStatistics
  }
}
</script>

<style scoped lang="scss">
  .dictionary {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 232px;
    height: 303px;
    background: rgba(0, 0, 0, 0.35);
    box-shadow: 0 4px 4px rgb(0, 0, 0);
    border-radius: 5px;
    margin-left: 60px;
    margin-top: 60px;

    .title {
      width: 232px;
      height: 43px;
      border-radius: 5px 5px 0 0;
      display: flex;
      justify-content: center;
      align-items: center;

      a {
        color: #fff;
        font-size: 23px;
      }
    }

    .main {

    }

    .statistics {
      width: 232px;
      height: 33px;
      border-radius: 0 0 5px 5px;
      color: #fff;
      font-size: 18px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: absolute;
      bottom: 0;

      .number {
        margin-left: 10px;
      }

      .percents {
        margin-right: 10px;
      }
    }

    .buttons {

      .btn-statistics, .btn-delete {
        position: absolute;
        bottom: -35px;
        box-sizing: border-box;
        border-radius: 3px;
        padding: 5px 15px;
        background-color: transparent;
        font-weight: bold;
        font-size: 12px;
        line-height: 14px;
        cursor: pointer;
        transition: .3s;

        &:hover {
          color: #FFFFFF;
        }
      }

      .btn-statistics {
        left: 0;
        border: 2px solid #363EFF;
        color: #363EFF;

        &:hover {
          background-color: #363EFF;
        }
      }

      .btn-delete {
        right: 0;
        border: 2px solid #FF4D4D;
        color: #FF4D4D;

        &:hover {
          background-color: #FF4D4D;
        }
      }
    }
  }
</style>