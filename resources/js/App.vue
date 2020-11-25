<template>
  <div v-if="isAppLoaded" id="app" class="h-full font-sans">
    <transition name="fade" mode="out-in">
      <router-view :key="$route.fullPath" />
    </transition>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  metaInfo: {
    title:
      'Je veux aider | Trouver des missions de bénévolat sur la Réserve Civique',
    // titleTemplate: '%s | JeVeuxAider.gouv.fr',
  },
  computed: {
    ...mapGetters(['isAppLoaded']),
  },
}
</script>
