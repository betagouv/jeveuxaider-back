import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/auth'
import user from './modules/user'
import app from './modules/app'

Vue.use(Vuex)

export default new Vuex.Store({
  strict: true,
  state: {
    isAppLoaded: false
  },

  modules: {
    auth,
    user,
    app
  },

  getters: {
    isAppLoaded(state){
      return state.isAppLoaded
    }
  },

  mutations: {
    UPDATE_APP_LOADING_STATUS(state, data) {
      state.isAppLoaded = data
    }
  },

  actions: {
    bootstrap({ commit, dispatch, state }) {
      return new Promise((resolve, reject) => {
        axios.get('/bootstrap').then((response) => {
          commit('user/SET_CURRENT_USER', response.data.user)
          commit('app/SET_TAXONOMIES', response.data.taxonomies)
          commit('app/SET_COLLABORATORS', response.data.collaborators)
          commit('app/SET_ACTIVITIES', response.data.activities)
          commit('UPDATE_APP_LOADING_STATUS', true)
          resolve(response)
        }).catch((err) => {
          reject(err)
        })
      })
    }
  }
})
