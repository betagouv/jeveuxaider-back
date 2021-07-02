import Vue from 'vue'
import VueTypedJs from 'vue-typed-js'
import VScrollLock from 'v-scroll-lock'
import Vue2Filters from 'vue2-filters'
import VClamp from 'vue-clamp'
import PortalVue from 'portal-vue'
import VTooltip from 'v-tooltip'
import vClickOutside from 'v-click-outside'
import VueClipboard from 'vue-clipboard2'
import TextareaAutosize from 'vue-textarea-autosize'
import Nl2br from 'vue-nl2br'
import VueTheMask from 'vue-the-mask'

Vue.use(VueClipboard)
Vue.use(vClickOutside)
Vue.use(VueTypedJs)
Vue.use(VScrollLock, {
  bodyScrollOptions: {
    reserveScrollBarGap: true,
  },
})
Vue.use(Vue2Filters)
Vue.use(PortalVue)

Vue.use(VTooltip, {
  defaultPopperOptions: {
    modifiers: {
      preventOverflow: {
        boundariesElement: 'offsetParent',
      },
    },
  },
  defaultDelay: 250,
  defaultOffset: 8,
})

Vue.use(vClickOutside)
Vue.use(TextareaAutosize)
Vue.use(VueTheMask)

Vue.component('VClamp', VClamp)
Vue.component('Nl2br', Nl2br)
