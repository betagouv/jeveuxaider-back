<template>
  <div id="app" v-if="isAppLoaded" class="h-full font-sans">
    <transition name="fade" mode="out-in">
        <router-view :key="$route.fullPath" />
    </transition>
  </div>
</template>

<script>
import { mapGetters } from "vuex"

export default {
  data() {
    return {
      message: null
    };
  },
  computed: {
    ...mapGetters(["isAppLoaded"])
  },
  created() {
    this.$store.dispatch("bootstrap")
  }
};
</script>
