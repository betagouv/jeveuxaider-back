import axios from 'axios'
import store from '../store'
import { Message } from 'element-ui'
import router from '../router'

// For sercurity reason
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// create an axios instance
const request = axios.create({
  baseURL: process.env.MIX_API_BASE_URL,
  // timeout: 5000 ( Ne convient pas pour l'export )
})

// request interceptor
request.interceptors.request.use(async (config) => {
  if (store.getters.contextRole) {
    config.headers['Context-Role'] = store.getters.contextRole
  }
  if (!config.headers.Authorization) {
    if (store.state.auth.accessTokenImpersonate) {
      config.headers[
        'Authorization'
      ] = `Bearer ${store.state.auth.accessTokenImpersonate}`
    } else if (store.state.auth.accessToken) {
      config.headers['Authorization'] = `Bearer ${store.state.auth.accessToken}`
    }
  }

  return config
})

// response interceptor
request.interceptors.response.use(
  (response) => response,
  (error) => {
    console.log('ERROR', error.response)
    if (error.response.status == 403) {
      console.log('ERROR DATA', error.response.data)
      router.push('/403')
      if (error.response.data.message) {
        Message({
          message: error.response.data.message,
          dangerouslyUseHTMLString: true,
          type: 'error',
        })
      }
    }
    else if (error.response && error.response.data) {
      if (
        error.response.data.message === 'Unauthenticated.' &&
        store.getters.isLogged
      ) {
        store.dispatch('auth/logout')
      } else if (error.response.data.errors) {
        Message({
          message: format_errors(error.response.data.errors),
          dangerouslyUseHTMLString: true,
          type: 'error',
        })
      } else if (error.response.data.message) {
        Message({
          message: error.response.data.message,
          dangerouslyUseHTMLString: true,
          type: 'error',
        })
      } else if (
        error.response.data == "Missing or wrong 'Context-Role' header"
      ) {
        store.dispatch('user/get')
      } else {
        console.log(error.config.responseType)
        if (error.config.responseType == 'blob') {
          console.log('OK BLOB')
          return new Promise((resolve, reject) => {
            let reader = new FileReader()
            reader.onload = () => {
              error.response.data = JSON.parse(reader.result)
              resolve(Promise.reject(error))
            }

            reader.onerror = () => {
              reject(error)
            }

            reader.readAsText(error.response.data)
          })
        } else {
          Message({
            message: format_errors(error.response.data),
            dangerouslyUseHTMLString: true,
            type: 'error',
          })
        }
      }
    }
    return Promise.reject(error)
  }
)

function format_errors(errors) {
  var string = ''
  for (var errorField in errors) {
    string += errors[errorField][0] + '<br />'
  }
  return string
}

export default request
