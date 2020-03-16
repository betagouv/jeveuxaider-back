import Ls from '@/services/ls'
import router from '@/router.js'

export default {
  namespaced: true,

  state: {
    token: Ls.get('auth.token')
  },

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  mutations: {
    AUTH_SUCCESS (state, token) {
      state.token = token
    },
    AUTH_LOGOUT (state) {
      state.token = null
    }
  },

  actions: {
    login({ commit, dispatch }, credentials) {
      return new Promise((resolve, reject) => {
        axios.post('/auth/login', credentials).then((response) => {
          let token = response.data.access_token
          Ls.set('auth.token', token)
          dispatch('user/getCurrentUser', null, { root: true })
          commit('AUTH_SUCCESS', token)
          resolve(response)
        }).catch(err => {
          commit('AUTH_LOGOUT', err.response)
          Ls.remove('auth.token')
          reject(err)
        })
      })
    },
    logout({ commit }) {
      return new Promise((resolve, reject) => {
        axios.get('/auth/logout').then((response) => {
          commit('AUTH_LOGOUT')
          Ls.remove('auth.token')
          router.push('/login')
          // window.toastr['success']('Logged out!', 'Success')
        }).catch(err => {
          reject(err)
          commit('AUTH_LOGOUT')
          Ls.remove('auth.token')
          router.push('/login')
        })
      })
    },
    register({ commit, dispatch }, data) {
      return new Promise((resolve, reject) => {
        axios.post('/auth/register', data).then((response) => {
          dispatch('login', data).then((response) => {
            resolve(response)
          })
        }).catch(err => {
          reject(err)
        })
      })
    },
  }
}
