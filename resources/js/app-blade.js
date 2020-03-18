import Vue from "vue";
import "./plugins/element.js";
// import router from "./router";
// import store from "./store";

import VueHeader from "@/components/VueHeader";

new Vue({
  el: '#app-blade',
  components: {
    VueHeader
  },
  // router,
  // store
})
