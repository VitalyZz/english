<template>
  <div class="main">
    <div class="title">Авторизация</div>
    <form @submit.prevent="submitHandler">
      <label for="email">Почта</label>
      <input type="text" id="email" name="email" class="email" v-model="email" autocomplete="off">

      <label for="password">Пароль</label>
      <input type="password" id="password" name="password" class="password" v-model="password">

      <div class="wrapper-btn">
        <button type="submit" class="btnLogin">Войти</button>
      </div>
    </form>
    <router-link class="createAccount" to="/signup">Создать аккаунт</router-link>
  </div>
</template>

<script>
export default {
  name: 'login',
  data() {
    return {
      email: '',
      password: '',
    }
  },
  methods: {
    async submitHandler() {
      if (this.email.length !== 0 && this.password.length !== 0) {
        const formData = {
          email: this.email,
          password: this.password
        }

        console.log('formData', formData)

        await this.$store.dispatch('auth/login', formData);
        await this.$router.push('/');
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .main {
    width: 400px;
    height: 370px;
    border: 5px solid #0DFF92;
    border-radius: 3px;
    position: relative;

    .title {
      font-weight: bold;
      font-size: 23px;
      color: #36404A;
      width: 100%;
      height: 60px;
      background: #0DFF92;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    form {
      display: flex;
      flex-direction: column;
      padding: 20px 40px;

      label {
        font-weight: bold;
        font-size: 18px;
        line-height: 21px;
        color: #0DFF92;
        margin-left: 15px;
        margin-bottom: 5px;
      }

      input {
        border: 3px solid #0DFF92;
        border-radius: 100px;
        padding: 10px 15px;
        background-color: transparent;
        margin-bottom: 15px;
        outline: none;
        font-weight: bold;
        font-size: 18px;
        color: #0DFF92;
      }

      .wrapper-btn {
        margin: 15px auto 0;

        .btnLogin {
          background: #0DFF92;
          border-radius: 30px;
          font-weight: bold;
          font-size: 18px;
          color: #36404A;
          border: none;
          padding: 15px 80px;
          cursor: pointer;
        }
      }
    }

    .createAccount {
      font-weight: bold;
      font-size: 15px;
      color: #0DFF92;
      position: absolute;
      bottom: 10px;
      left: 10px;
    }
  }
</style>