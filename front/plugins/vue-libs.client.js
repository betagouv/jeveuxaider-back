import Vue from 'vue'
import VueTypedJs from 'vue-typed-js'
import VScrollLock from 'v-scroll-lock'
import Vue2Filters from 'vue2-filters'
import VClamp from 'vue-clamp'
import PortalVue from 'portal-vue'
import VTooltip from 'v-tooltip'

Vue.use(VueTypedJs)
Vue.use(VScrollLock)
Vue.use(Vue2Filters)
Vue.use(PortalVue)
Vue.use(VTooltip)

Vue.component('VClamp', VClamp)
