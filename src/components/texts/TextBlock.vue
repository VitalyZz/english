<template>
  <div class="text">
    <div class="title">
      <h2>
        <router-link :to="`/text/${id_text}`">{{ title }}</router-link>
      </h2>
    </div>
    <div class="main">
      <p class="content">
        {{ text }}
      </p>
      <div class="dark"></div>
    </div>
    <div class="buttons">
      <button class="delete" :data-user-id="id_user">Удалить</button>
      <router-link class="forward" :to="`/text/${id_text}`" :data-user-id="id_user">Вперед</router-link>
      <button
          class="statistics"
          @click="showModalStatistics = true">
        Статистика
      </button>
    </div>
  </div>

  <teleport to="body">
    <ModalStatistics
      v-if="showModalStatistics"
      @closeModalStatistics="showModalStatistics = false"
    />
  </teleport>
</template>

<script>
import ModalStatistics from "@/components/texts/ModalStatistics";

export default {
  props: ['title', 'text', 'id_user', 'id_text'],
  data() {
    return {
      showModalStatistics: false
    }
  },
  components: {
    ModalStatistics
  }
}
</script>

<style scoped lang="scss">
  .text {
    position: relative;
    box-sizing: border-box;
    box-shadow: 10px 10px 15px -3px #000000;
    margin: 40px auto 0;
    display: flex;
    flex-wrap: nowrap;
    align-content: center;
    justify-content: space-between;
    border-radius: 5px;
    min-height: 170px;
    transition: .3s;
    width: 100%;
    flex-direction: column;

    .title {
      padding: 10px 0;
      position: relative;
      text-align: center;
      background-color: #0DFF92;
      h2 {
        a {
          font-weight: bold;
          font-size: 30px;
          color: #36404A;
        }
      }
    }

    .main {
      height: 175px;
      position: relative;
      overflow: hidden;

      .content {
        padding: 5px 0 0 5px;
        font-size: 23px;
        color: #FFFFFF;
        max-height: 170px;
        overflow: hidden;
      }

      .dark {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, black 0%, rgba(0, 0, 0, 0.7) 100%);
      }
    }

    .buttons {
      position: absolute;
      bottom: 0;
      left: 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      z-index: 5;
      padding: 0 0 10px;

      button.delete, a.forward, button.statistics {
        font-weight: bold;
        font-size: 14px;
        background: transparent;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        transition: .3s;

        &:hover {
          color: #fff;
        }
      }

      button.delete, a.forward, button.statistics {
        border: 2px solid #FF4D4D;
        color: #FF4D4D;
        margin-left: 10px;

        &:hover {
          background-color: #FF4D4D;
          box-shadow: 0 0 20px 3px #FF4D4D;
        }
      }

      a.forward {
        color: #06bb0c;
        border: 2px solid #06bb0c;

        &:hover {
          background-color: #06bb0c;
          box-shadow: 0 0 20px 3px #06bb0c;
        }
      }

      button.statistics {
        color: #363EFF;
        border: 2px solid #363EFF;
        margin-right: 10px;

        &:hover {
          background-color: #363EFF;
          box-shadow: 0 0 20px 3px #363EFF;
        }
      }
    }
  }
</style>