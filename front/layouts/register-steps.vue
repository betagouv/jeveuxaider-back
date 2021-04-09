<template>
  <el-container class="w-full h-full flex flex-wrap lg:flex-no-wrap">
    <div
      class="aside flex flex-wrap sm:flex-no-wrap lg:flex-col w-full gradient-primary"
    >
      <div
        class="flex-grow lg:flex-grow-0 text-white px-6 py-5 m-4 lg:mx-10 lg:mt-24 rounded-lg order-2 lg:order-1"
        style="background: rgba(255, 255, 255, 0.2)"
      >
        <portal-target
          class="text-center text-sm font-light"
          name="register-steps-help"
        />
      </div>

      <div
        class="flex sm:flex-col sm:flex-none items-center lg:mt-auto w-full sm:w-auto lg:w-full p-4 order-1 lg:order-2"
      >
        <img
          src="@/assets/images/JVA_dark.svg"
          class="logo sm:mb-2 lg:mb-2 h-7 lg:h-24 w-auto"
        />
        <div
          class="flex justify-center text-blue-200 font-light border-blue-400 ml-auto sm:ml-0 sm:w-full sm:border-t"
        >
          <div
            v-if="isImpersonating"
            class="text-sm p-6 cursor-pointer hover:text-white"
            @click="$store.dispatch('auth/stopImpersonate')"
          >
            Unmasquerade
          </div>
          <div
            v-else
            class="text-sm sm:pt-2 lg:p-6 cursor-pointer hover:text-white"
            @click="$store.dispatch('auth/logout')"
          >
            Se déconnecter
          </div>
        </div>
      </div>
    </div>
    <div class="main-content flex-grow flex flex-col">
      <Nuxt :nuxt-child-key="$route.fullPath" class="app-main" />
    </div>
  </el-container>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'RegisterStepsLayout',
  middleware: 'logged',
  computed: {
    ...mapGetters(['isImpersonating']),
  },
}
</script>

<style lang="sass" scoped>
.aside
  @screen lg
    position: fixed
    max-width: 390px
    min-height: 100vh
.main-content
  @screen lg
    margin-left: 390px
.logo
  width: 150px
  @screen lg
    max-width: 230px
</style>
