<template>
  <div
    v-if="isAppLoaded"
    id="app"
    class="h-full font-sans"
  >
    <transition
      name="fade"
      mode="out-in"
    >
      <router-view :key="$route.fullPath" />
    </transition>
  </div>
</template>

<script>
import { mapGetters } from "vuex"

export default {
  computed: {
    ...mapGetters(["isAppLoaded"])
  }
};
</script>
