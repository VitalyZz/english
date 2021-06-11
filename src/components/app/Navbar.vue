<template>
  <header v-if="isShow">
    <div class="start">
      <router-link to="/texts" class="brand-logo">English-lan</router-link>
    </div>
    <div class="center" v-if="getAuth">
      <ul class="navigation">
        <router-link
            v-for="link in links"
            :key="link.url"
            :to="link.url"
            custom
            v-slot="{ isActive, href, navigate }">
          <li :class="isActive ? 'active' : ''">
            <a :href="href" @click="navigate">{{ link.title }}</a>
          </li>
        </router-link>
      </ul>
    </div>

    <div class="end" v-if="getAuth">
      <a @click="logout">Выход</a>
    </div>

    <div class="notAuth" v-else>
      <router-link to="/login" class="login">Авторизация</router-link>
      <router-link to="/signup" class="signup">Регистрация</router-link>
    </div>
  </header>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data() {
    return {
      isLogin: false,
      isShow: false,
      links: [
        {title: 'Тексты', url: '/texts'},
        {title: 'Словари', url: '/dictionaries'},
        {title: 'Статистика', url: '/statistics'},
        {title: 'Тренажеры', url: '/simulators'},
      ]
    }
  },
  computed: {
    ...mapGetters('auth', ['getCurrentUser', 'getAuth'])
  },
  mounted() {
    const checkAuth = this.getAuth;
    // const checkAuth = (localStorage.isAuth !== undefined && localStorage.isAuth !== 'false') ?? true;

    console.log('Navbar, checkAuth [Navbar]', checkAuth)

    if (checkAuth) this.isLogin = true
    this.isShow = true

    // setTimeout(() => {
    //   const checkAuth = this.$store.getters['auth/getAuth'];
    //   console.log('Navbar, checkAuth: setTimeout', checkAuth)
    // }, 100)
  },
  methods: {
    logout() {
      this.$store.dispatch('auth/logout');
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped lang="scss">
  header {
    position: fixed;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    height: 70px;
    background-color: #0DFF92;
    z-index: 2;
  }

  .center {
    .navigation {
      display: flex;
      align-items: center;
      justify-content: space-between;
      list-style: none;
      padding: 0;

      li:not(:last-child) {
        margin-right: 35px;
      }

      .active {
        color: #007dff;
        border-bottom: 2px solid #36404A;
      }

      li {
        border-bottom: 2px solid transparent;
        transition: .3s;

        a {
          font-weight: bold;
          font-size: 23px;
          line-height: 30px;
          color: #36404A;
        }

        &:hover {
          border-bottom: 2px solid #36404A;
          transition: .3s;
        }
      }
    }
  }

  .start {
    margin-left: 20px;
    a {
      font-family: Ubuntu, sans-serif;
      font-style: normal;
      font-weight: bold;
      font-size: 40px;
      color: #36404A;
    }
  }

  .end {
    margin-right: 20px;

    a {
      font-weight: bold;
      font-size: 23px;
      line-height: 30px;
      color: #36404A;
      transition: .3s;
      cursor: pointer;

      &:hover {
        color: #ff6363;
        transition: .3s;
      }
    }
  }

  .notAuth {
    margin-right: 20px;

    .login {
      margin-right: 10px;
    }

    a {
      font-weight: bold;
      font-size: 23px;
      line-height: 30px;
      color: #36404A;
      transition: .3s;
    }
  }
</style>