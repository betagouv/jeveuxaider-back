import VueRouter from 'vue-router'
import Vue from 'vue'
import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/fr'
import axios from 'axios';
import Ls from './services/ls'
import store from './store/index.js'
import { Message } from "element-ui"
import Swal from 'sweetalert2'

window._ = require('lodash')
window.Vue = require('vue')
window.Swal = Swal

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

axios.defaults.baseURL = '/api';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Interceptors
 */

window.axios.interceptors.request.use(function (config) {
  // Do something before request is sent
  const AUTH_TOKEN = Ls.get('auth.token')

  if (AUTH_TOKEN) {
    config.headers.common['Authorization'] = `Bearer ${AUTH_TOKEN}`
  }

  return config
}, function (error) {
  // Do something with request error
  return Promise.reject(error)
})

/**
 * Global Axios Response Interceptor
 */
global.axios.interceptors.response.use(undefined, function (err) {
  // Do something with request error
  return new Promise((resolve, reject) => {
    console.log(err.response)
    if (err.response && err.response.data) {
      if (!err.response.data.errors) {
        Message({
          message: err.response.data.message,
          type: "error"
        });
      } else {
        Message({
          message: format_errors(err.response.data.errors),
          dangerouslyUseHTMLString: true,
          type: "error"
        });
      }
    }
    if (err.response.data && (err.response.data.message === 'Unauthenticated.')) {
      store.dispatch('auth/logout', true)
    } else {
      throw err
    }
  })
})

Vue.use(VueRouter)
Vue.use(ElementUI, { locale })

/**
 * Plugins
 */
require('./plugins/vue-font-awesome/index')
require('./plugins/dayjs/index')
require('./plugins/numeral/index')

function format_errors(errors) {
  var string = "";
  for (var errorField in errors) {
    string += errors[errorField][0] + "<br />";
  }
  return string;
}
