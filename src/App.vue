<template>
  <Navbar v-if="isDisplayNavbar"/>
  <component :is="layout">
    <router-view/>
  </component>
</template>

<script>
import Navbar from '@/components/app/Navbar'
import MainLayout from "@/layouts/MainLayout";
import EmptyLayout from "@/layouts/EmptyLayout";
import CenterLayout from "@/layouts/CenterLayout";
import { mapGetters } from 'vuex'
export default {
  name: 'app',
  data() {
    return {
      isDisplayNavbar: false
    }
  },
  computed: {
    ...mapGetters('auth', ['getCurrentUser', 'getAuth']),
    layout() {
      return this.$route.meta.layout + '-layout'
    }
  },
  mounted() {
    this.$store.dispatch('auth/loadCurrentUser')

    console.log('this.currentUser [APP]', this.getCurrentUser)
    console.log('this.getAuth [APP]', this.getAuth)

    this.isDisplayNavbar = true
  },
  components: {
    Navbar,
    MainLayout,
    EmptyLayout,
    CenterLayout
  }
}
</script>

<style lang="scss">
@import 'assets/css/main';
</style>
