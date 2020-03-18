<template>
  <el-container v-if="isAppLoaded" class="h-full flex">
    <div
      class="flex flex-col h-full  gradient-primary fixed flex-none w-1/4"
      style=""
    >
      <div
        class="text-white px-6 py-5 mx-10 rounded-lg"
        style="background: rgba(255, 255, 255, 0.2); margin-top: 95px;"
      >
        <portal-target
          class="text-center text-sm font-light"
          name="register-steps-help"
        />
      </div>

      <div class="absolute bottom-0 w-full flex flex-col items-center px-4">
        <img
          src="/images/logo-reserve-civique_dark.svg"
          class="mb-8"
          style="max-width: 230px;"
        />
        <div
          class="flex text-blue-200 font-light border-t border-blue-400 w-full justify-center"
        >
          <div
            v-if="isImpersonating"
            class="text-sm p-6 cursor-pointer hover:text-white"
            @click="$store.dispatch('auth/stopImpersonate')"
          >
            Unmasquerade
          </div>
          <div
            v-else
            class="text-sm p-6 cursor-pointer hover:text-white"
            @click="$store.dispatch('auth/logout')"
          >
            Se déconnecter
          </div>
        </div>
      </div>
    </div>
    <div class="flex flex-col w-full" style="width:75%; margin-left:25%;">
      <router-view class="app-main" />
    </div>
  </el-container>
</template>

<script>
import { mapGetters } from "vuex";
import store from "@/store";

export default {
  name: "RegisterSteps",
  computed: {
    ...mapGetters(["isAppLoaded", "isImpersonating"])
  },
  created() {
    this.$store.dispatch("bootstrap");
  },
  beforeRouteEnter(to, from, next) {
    // called before the route that renders this component is confirmed.
    // does NOT have access to `this` component instance,
    // because it has not been created yet when this guard is called!
    if (!store.getters.noRole && to.name != "AddressStep") {
      next("/");
    }
    next();
  }
};
</script>
