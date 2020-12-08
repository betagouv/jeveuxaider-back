<template>
  <div v-if="isAppLoaded" id="app" class="h-full font-sans">
    <transition name="fade" mode="out-in">
      <component :is="layout">
        <router-view :key="$route.fullPath" />
      </component>
    </transition>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  metaInfo: {
    title:
      'Je veux aider | Trouver des missions de bénévolat sur la Réserve Civique',
  },
  computed: {
    ...mapGetters(['isAppLoaded']),
    layout() {
      return (this.$route.meta.layout || 'default') + '-layout'
    },
  },
}
</script>
