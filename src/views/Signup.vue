<template>
  <div class="main">
    <div class="title">Регистрация</div>
    <form @submit.prevent="submitHandler">
      <label for="email">Почта</label>
      <input type="text" id="email" name="email" class="email" v-model="email">

      <label for="password">Пароль</label>
      <input type="password" id="password" name="password" class="password" v-model="password">

      <label for="password_confirm">Повторите пароль</label>
      <input type="password" id="password_confirm" name="password_confirm" class="password_confirm" v-model="passwordConfirm">

      <div class="wrapper-btn">
        <button type="submit" class="btnSignup">Зарегистрироваться</button>
      </div>
    </form>

    <router-link to="/login" class="login">Войти</router-link>
  </div>
</template>

<script>
export default {
  name: 'signup',
  data() {
    return {
      email: '',
      password: '',
      passwordConfirm: '',
    }
  },
  methods: {
    async submitHandler() {
      if (this.email.length !== 0 && this.password.length !== 0 && this.password === this.passwordConfirm) {
        const formData = {
          email: this.email,
          password: this.password,
          password_confirm: this.passwordConfirm
        }

        console.log('formData', formData)

        await this.$store.dispatch('auth/signup', formData);
        await this.$router.push('/');
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .main {
    position: relative;
    width: 400px;
    height: 450px;
    border: 5px solid #0DFF92;
    border-radius: 3px;

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

        .btnSignup {
          background: #0DFF92;
          border-radius: 30px;
          font-weight: bold;
          font-size: 18px;
          color: #36404A;
          border: none;
          padding: 15px 25px;
          cursor: pointer;
        }
      }
    }

    .login {
      font-weight: bold;
      font-size: 15px;
      color: #0DFF92;
      position: absolute;
      bottom: 10px;
      left: 10px;
    }
  }
</style>