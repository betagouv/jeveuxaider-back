<template>
  <div class="bg-gray-100 h-full flex flex-col">
    <AppHeader />
    <Nuxt :nuxt-child-key="$route.fullPath" />
    <transition name="fade">
      <LazySearchOverlay
        v-if="$store.getters.searchOverlay"
        @submitted="$store.commit('toggleSearchOverlay')"
        @closed="$store.commit('toggleSearchOverlay')"
      />
    </transition>
  </div>
</template>

<script>
export default {
  name: 'MessagesLayout',
  middleware: 'logged',
  head: {
    bodyAttrs: {
      class: 'full-height-layout',
    },
  },
}
</script>
