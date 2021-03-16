<template>
  <div class="bg-gray-100 overflow-x-hidden">
    <AppHeader />
    <Nuxt />
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
