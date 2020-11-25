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
    title: 'Engagez-vous dans la Réserve Civique',
    titleTemplate: '%s | JeVeuxAider.gouv.fr',
  },
  computed: {
    ...mapGetters(['isAppLoaded']),
  },
}
</script>
