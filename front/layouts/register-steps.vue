<template>
  <div class="w-full lg:h-full flex flex-col lg:flex-row">
    <div class="bg-white lg:w-1/3 lg:h-full">
      <div class="p-6 lg:p-12 border-b border-cool-gray-100">
        <nuxt-link to="/">
          <img
            src="@/assets/images/jeveuxaider-logo.svg"
            alt="Bénévolat je veux aider"
            title="Bénévolat association"
            class="mx-auto"
            width="243px"
            height="39px"
          />
        </nuxt-link>
      </div>
      <div class="p-6 lg:p-12">
        <portal-target name="sidebar"></portal-target>
      </div>
    </div>
    <div class="lg:w-2/3 relative bg-blue-800 lg:overflow-auto lg:h-full">
      <img
        class="z-1 object-cover absolute h-screen lg:h-auto"
        alt="Je Veux Aider"
        :srcSet="bgHeroMultipleSizes.srcSet"
        :src="bgHeroMultipleSizes.src"
        width="100%"
        height="100%"
      />
      <div class="p-6 lg:p-12">
        <Nuxt :nuxt-child-key="$route.fullPath" class="" />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
const bgHeroMultipleSizes = require('@/assets/images/bg-jva.jpg?resize&sizes[]=320&sizes[]=640&sizes[]=960&sizes[]=1200&sizes[]=1800&sizes[]=2400&sizes[]=3900')

export default {
  name: 'RegisterStepsLayout',
  middleware: 'logged',
  data() {
    return {
      bgHeroMultipleSizes,
    }
  },
  head: {
    bodyAttrs: {
      class: 'full-height-layout',
    },
  },
  computed: {
    ...mapGetters(['isImpersonating']),
  },
  methods: {
    handleLogout() {
      this.$router.push('/')
      this.$store.dispatch('auth/logout')
    },
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
