import { library } from '@fortawesome/fontawesome-svg-core'
import {
  faUser,
  faUsers,
  faUserFriends,
  faChartBar,
  faTh,
  faFolder,
  faSpinner
} from '@fortawesome/free-solid-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

window.Vue = require('vue')

library.add(
  far,
  faUser,
  faUsers,
  faUserFriends,
  faChartBar,
  faTh,
  faFolder,
  faSpinner
)

Vue.component('font-awesome-icon', FontAwesomeIcon)
