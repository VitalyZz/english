<template>
  <div class="main">
    <div class="block">
      <form @submit.prevent="$emit('addText', title, text)">
        <h2>Заполните поля</h2>
        <div class="error"></div>
        <div class="form-control">
          <input type="text" name="title" id="inputId" placeholder=" " v-model="title" autocomplete="off">
          <label for="inputId" class="labelClass labelForInput">Заголовок</label>
        </div>
        <div class="form-control">
          <textarea id="textareaId" name="text" cols="30" rows="10" placeholder=" " v-model="text"></textarea>
          <label for="textareaId" class="labelClass labelForTextarea">Текст</label>
        </div>
        <div class="wrapperBtn">
          <button>Добавить</button>
        </div>
      </form>
    </div>
    <div class="close" @click="$emit('closeModalAddText')"></div>
  </div>

  <div class="dark" @click="$emit('closeModalAddText')"></div>
</template>

<script>
export default {
  emits: ['closeModalAddText', 'addText'],
  data() {
    return {
      title: '',
      text: ''
    }
  }
}
</script>

<style scoped lang="scss">
  .main {
    height: 550px;
    width: 500px;
    background-color: #36404A;
    position: absolute;
    top: calc(50vh - 250px);
    left: calc(50vw - 250px);
    z-index: 10;
    border-radius: 15px;

    .block {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;

      form {
        padding: 32px 32px 0 32px;

        h2 {
          text-align: center;
          margin-bottom: 10px;
          color: #0DFF92;
        }

        .error {
          display: none;
          text-align: center;
          font-size: 15px;
          font-weight: bold;
          color: #FF4D4D;
          margin-bottom: 10px;
        }

        .form-control {
          position: relative;
          margin-bottom: 32px;

          input, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            background-color: transparent;
            outline: none;
            transition: .3s;
            z-index: -1;
            border: 2px solid #0DFF92;
            color: #fff;
            font-size: 18px;

            &:focus ~ .labelClass, &:not(:placeholder-shown) ~ .labelClass {
              top: -10px;
              left: -10px;
              font-size: 15px;
              transform: rotate(-90deg);
              z-index: 2;
            }

            &:placeholder-shown {
              border: 2px solid #FF4D4D;

              ~ .labelClass {
                color: #FF4D4D;
              }
            }

            ~ .labelClass {
              color: #0DFF92;
            }

            &:focus ~ .labelForInput, &:not(:placeholder-shown) ~ .labelForInput {
              top: -10px;
              left: -50px;
              transform: rotate(-90deg);
            }

            &:focus ~ .labelForTextarea, &:not(:placeholder-shown) ~ .labelForTextarea {
              top: 10px;
              left: -30px;
              transform: rotate(-90deg);
            }
          }

          textarea {
            resize: none;
            width: 100%;
            padding: 10px;
            border: 2px solid #000;
            border-radius: 5px;
            background-color: transparent;
            outline: none;
            transition: .3s;
            z-index: -1;
            border: 2px solid #0DFF92;
            color: #fff;
            font-size: 18px;

            &:placeholder-shown {
              border: 2px solid #FF4D4D;
            }
          }

          label {
            position: absolute;
            top: 8px;
            left: 8px;
            font-size: 20px;
            font-weight: bold;
            color: #9e9e9e;
            z-index: -1;
            transition: .3s;

            & ~ & {
              color: #0DFF92;
            }

            &:placeholder-shown ~ & {
              color: #FF4D4D;
            }
          }
        }

        .wrapperBtn {
          display: flex;
          justify-content: center;

          button {
            background-color: transparent;
            color: #0DFF92;
            font-size: 18px;
            font-weight: bold;
            line-height: 18px;
            border: 2px solid #0DFF92;
            box-sizing: border-box;
            border-radius: 3px;
            padding: 5px 10px;
            cursor: pointer;

            &:hover {
              color: #36404A;
              background-color: #0DFF92;
            }
          }
        }
      }
    }

    .close {
      position: absolute;
      color: #fff;
      top: 10px;
      right: 10px;
      cursor: pointer;
      width: 20px;
      height: 20px;

      &:before {
        position: absolute;
        content: '';
        width: 4px;
        height: 20px;
        transform: rotate(-45deg);
        background-color: #fff;
        top: calc(50% - 10px);
        right: calc(50% - 2px);
        transition: .3s;
      }

      &:after {
        position: absolute;
        content: '';
        width: 4px;
        height: 20px;
        transform: rotate(45deg);
        background-color: #fff;
        top: calc(50% - 10px);
        right: calc(50% - 2px);
        transition: .3s;
      }

      &:hover:before {
        background-color: #ff392e;
      }

      &:hover:after {
        background-color: #ff392e;
      }
    }
  }

  .dark {
    display: block;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    position: absolute;
    top: 0;
    left: 0;
    z-index: 2;
  }
</style>