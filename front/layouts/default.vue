<template>
  <div class="bg-gray-100 overflow-x-hidden">
    <div class="nav-skip" lang="fr">
      <ul id="top">
        <li>
          <a href="#main">Aller au contenu</a>
        </li>
        <li>
          <a href="#search">Aller à la recherche</a>
        </li>
      </ul>
    </div>
    <AppHeader />
    <main id="main">
      <Nuxt />
    </main>
    <AppFooter />
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
export default {
  name: 'DefaultLayout',
}
</script>

<style lang="sass" scoped>
.nav-skip
  padding: .5em
  display: inline-block
  position: absolute
  left: -99999rem
  z-index: 100

.nav-skip:focus-within
  position: relative
  display: block
  left: 0
</style>
