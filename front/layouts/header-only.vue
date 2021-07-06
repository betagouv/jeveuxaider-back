<template>
  <div class="relative bg-blue-800 h-full">
    <AppHeader />
    <img
      class="z-1 object-cover absolute"
      alt="Je Veux Aider"
      :srcSet="bgHeroMultipleSizes.srcSet"
      :src="bgHeroMultipleSizes.src"
      width="100%"
      height="100%"
    />
    <div class="p-6 lg:p-12">
      <Nuxt :nuxt-child-key="$route.fullPath" class="" />
    </div>
    <transition name="fade">
      <LazySearchOverlay
        v-if="$store.getters.searchOverlay"
        @submitted="$store.commit('toggleSearchOverlay')"
        @closed="$store.commit('toggleSearchOverlay')"
      />
      <LazySoftGateOverlay
        v-if="$store.getters.softGateOverlay"
        @submitted="$store.commit('toggleSoftGateOverlay')"
        @closed="$store.commit('toggleSoftGateOverlay')"
      />
    </transition>
  </div>
</template>

<script>
const bgHeroMultipleSizes = require('@/assets/images/bg-jva.jpg?resize&sizes[]=320&sizes[]=640&sizes[]=960&sizes[]=1200&sizes[]=1800&sizes[]=2400&sizes[]=3900')

export default {
  name: 'HeaderOnlyLayout',
  data() {
    return {
      bgHeroMultipleSizes,
    }
  },
  head: {
    bodyAttrs: {
      class: 'h-full',
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
