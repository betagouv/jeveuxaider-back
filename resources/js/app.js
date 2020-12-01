import Vue from 'vue'

import App from './App.vue'
import router from './router'
import './plugins/element.js'
import store from './store'
import './router/permission'
import PortalVue from 'portal-vue'
import './plugins/sentry.js'
import Bowser from 'bowser'
import './plugins/numeral.js'
import './plugins/dayjs.js'
import './plugins/font-awesome.js'
import './plugins/textarea-autosize.js'
import './plugins/nl2br.js'
import './plugins/utils.js'
import './plugins/prettyBytes.js'
import Vue2Filters from 'vue2-filters'
import VueAnalytics from 'vue-analytics'
import VueTheMask from 'vue-the-mask'

import AppHeader from '@/components/AppHeader'
import AppFooter from '@/components/AppFooter'
import DropdownUser from '@/components/DropdownUser'
import VClamp from 'vue-clamp'
import CKEditor from '@ckeditor/ckeditor5-vue'
import vClickOutside from 'v-click-outside'

Vue.component('AppHeader', AppHeader)
Vue.component('AppFooter', AppFooter)
Vue.component('DropdownUser', DropdownUser)
Vue.component('VClamp', VClamp)

Vue.use(Vue2Filters)
Vue.use(PortalVue)
Vue.use(CKEditor)
Vue.use(vClickOutside)
Vue.use(VueAnalytics, {
  id: 'UA-81479189-7',
  router,
  autoTracking: {
    pageviewTemplate(route) {
      return {
        page: route.path,
        title: document.title,
        location: window.location.href,
      }
    },
  },
})

Vue.use(VueTheMask)

// Vue.directive('mask', {
//   bind: function (el, binding) {
//     Inputmask(binding.value).mask(el.getElementsByTagName('INPUT')[0])
//   },
// })

new Vue({
  router,
  store,
  components: {
    AppHeader,
    AppFooter,
  },
  async created() {
    await this.$store.dispatch('bootstrap')
    // Non supported version of browser (IE < 11)
    const browserInfos = Bowser.parse(window.navigator.userAgent)
    if (
      browserInfos.browser.name == 'Internet Explorer' &&
      browserInfos.browser.version != '11.0'
    ) {
      this.$router.push('/browser-outdated')
    }

    Crisp.on_load = function() { // eslint-disable-line
      if (typeof store.getters.profile !== 'undefined') {
          $crisp.push(["set", "user:email", [store.getters.profile.email]]); // eslint-disable-line
          $crisp.push(["set", "user:nickname", [store.getters.profile.full_name]]); // eslint-disable-line
      }
      if (typeof store.getters.profile.zip !== 'undefined') {
          $crisp.push(["set", "session:data", ["code_postal",store.getters.profile.zip]]); // eslint-disable-line
      }
      if (
        typeof store.getters.contextRole !== 'undefined' &&
        store.getters.contextRole != null
      ) {
          $crisp.push(["set", "session:data", ["role",store.getters.contextRole]]); // eslint-disable-line
      }
    }

    router.afterEach(() => {
      store.commit('volet/hide')
      store.commit('setLoading', false)
    })
    router.beforeEach((to, from, next) => {
      store.commit('setLoading', true)
      next()
    })
  },
  render: (h) => h(App),
}).$mount('#app')
