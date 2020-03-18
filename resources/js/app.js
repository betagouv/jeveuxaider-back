import Vue from "vue";

import App from "./App.vue";
import router from "./router";
import "./plugins/element.js";
import store from "./store";
import "./router/permission";
import PortalVue from "portal-vue";

import AppHeader from "@/components/AppHeader"
import AppFooter from "@/components/AppFooter"
import DropdownUser from "@/components/DropdownUser"

Vue.component('AppHeader', AppHeader);
Vue.component('AppFooter', AppFooter);
Vue.component('DropdownUser', DropdownUser);

/*
import Bowser from "bowser";
import Vue2Filters from "vue2-filters";

import "./plugins/sentry.js";
import "./plugins/dayjs.js";
import "./plugins/utils.js";
Vue.config.productionTip = false;

Vue.use(Vue2Filters);
*/
Vue.use(PortalVue);

new Vue({
  router,
  store,
  components: {
    AppHeader,
    AppFooter
  },
  created() {
    // Non supported version of browser (IE < 11)
    /*
    const browserInfos = Bowser.parse(window.navigator.userAgent);
    if (
      browserInfos.browser.name == "Internet Explorer" &&
      browserInfos.browser.version != "11.0"
    ) {
      this.$router.push("/browser-outdated");
    }
    */
    router.afterEach(() => {
      store.commit("volet/hide");
      store.commit("setLoading", false);
    });
    router.beforeEach((to, from, next) => {
      store.commit("setLoading", true);
      next();
    });
  },
  render: h => h(App)
}).$mount("#app");
