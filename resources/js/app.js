import Vue from "vue";

import App from "./App.vue";
import router from "./router";
import "./plugins/element.js";
import store from "./store";
import "./router/permission";
import PortalVue from "portal-vue";
import "./plugins/sentry.js";
import Bowser from "bowser";
import "./plugins/numeral.js";
import "./plugins/dayjs.js";
import "./plugins/utils.js";
import Vue2Filters from "vue2-filters";
import VueAnalytics from 'vue-analytics'
import Inputmask from 'inputmask'

import AppHeader from "@/components/AppHeader"
import AppFooter from "@/components/AppFooter"
import DropdownUser from "@/components/DropdownUser"
import VClamp from "vue-clamp";

Vue.component('AppHeader', AppHeader);
Vue.component('AppFooter', AppFooter);
Vue.component('DropdownUser', DropdownUser);
Vue.component('VClamp', VClamp);

Vue.use(Vue2Filters);
Vue.use(PortalVue);
Vue.use(VueAnalytics, {
  id: 'UA-81479189-7',
  router,
  autoTracking: {
    pageviewTemplate(route) {
      return {
        page: route.path,
        title: document.title,
        location: window.location.href
      }
    }
  }
})

Vue.directive('mask', {
  bind: function (el, binding) {
    Inputmask(binding.value).mask(el.getElementsByTagName('INPUT')[0])
  }
})

new Vue({
  router,
  store,
  components: {
    AppHeader,
    AppFooter
  },
  created() {
    // Non supported version of browser (IE < 11)
    const browserInfos = Bowser.parse(window.navigator.userAgent);
    if (
      browserInfos.browser.name == "Internet Explorer" &&
      browserInfos.browser.version != "11.0"
    ) {
      this.$router.push("/browser-outdated");
    }

    Crisp.on_load = function() {
      if (! ( window.location.href.includes('dashboard')|| window.location.href.includes('step') ) ) {
        $crisp.push(['do', 'chat:hide']);
       
      } else {
        $crisp.push(['do', 'chat:show']);
        if (store.getters.profile) {
          $crisp.push(["set", "user:email", [store.getters.profile.email]]);
          $crisp.push(["set", "user:nickname", [store.getters.profile.full_name]]);
          $crisp.push(["set", "session:data", ["code_postal",store.getters.profile.zip]]);
          $crisp.push(["set", "session:data", ["role",store.getters.contextRole]]);
        }
       
      }
    };

    router.afterEach((to, from) => {
      store.commit("volet/hide");
      store.commit("setLoading", false);
    });
    router.beforeEach((to, from, next) => {
      store.commit("setLoading", true);
      if (! (to.path.includes('dashboard')|| (to.path.includes('step')) )) {
        $crisp.push(['do', 'chat:hide']);
      
      } else {
        $crisp.push(['do', 'chat:show']);
        if (store.getters.profile) {
          $crisp.push(["set", "user:email", [store.getters.profile.email]]);
          $crisp.push(["set", "user:nickname", [store.getters.profile.full_name]]);
          $crisp.push(["set", "session:data", ["code_postal",store.getters.profile.zip]]);
          $crisp.push(["set", "session:data", ["role",store.getters.contextRole]]);
        }
      }
      next();
    });
  },
  render: h => h(App)
}).$mount("#app");
