<template>
  <el-container v-if="isAppLoaded" class="h-full flex overflow-hidden">
    <sidebar class="bg-gray-100" />
    <div
      v-loading="$store.getters.loading"
      class="main-container overflow-y-auto w-full"
    >
      <router-view :key="$route.fullPath" class="app-main pt-8" />
    </div>
    <portal-target
      v-if="$store.getters['volet/active'] && $store.getters['volet/row']"
      class="p-4 overflow-y-auto flex-none border-l bg-gray-100"
      name="volet"
      style="width: 420px"
    />
  </el-container>
</template>

<script>
import { mapGetters } from "vuex";
import Sidebar from "./components/Sidebar";
import store from "@/store";

export default {
  name: "Layout",
  components: {
    Sidebar
  },
  beforeRouteEnter(to, from, next) {
    // called before the route that renders this component is confirmed.
    // does NOT have access to `this` component instance,
    // because it has not been created yet when this guard is called!
    if (store.getters.noRole) {
      next("/register/step/norole");
    }
    next();
  },
  computed: {
    ...mapGetters(["isAppLoaded"])
  },
  created() {
    this.$store.dispatch("bootstrap");
  }
};
</script>
