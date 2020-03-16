export default {
  namespaced: true,

  state: {
    currentUser: null
  },

  getters: {
    currentUser: (state) => state.currentUser,
    shortName: (state) => `${state.currentUser.profile.first_name[0]}${state.currentUser.profile.last_name[0]}`
  },

  mutations: {
    SET_CURRENT_USER (state, user) {
      state.currentUser = user
    },
  },

  actions: {
    getCurrentUser({ commit, dispatch, state }, data) {
      return new Promise((resolve, reject) => {
        axios.get('/auth/user', data).then((response) => {
          commit('SET_CURRENT_USER', response.data)
          resolve(response)
        }).catch((err) => {
          reject(err)
        })
      })
    },
    updateCurrentUser({ commit, dispatch, state }, data) {
      return new Promise((resolve, reject) => {
        axios.post('/user', data).then((response) => {
          commit('SET_CURRENT_USER', response.data)
          resolve(response)
        }).catch((err) => {
          reject(err)
        })
      })
    },
    updateCurrentUserPassword({ commit, dispatch, state }, data) {
      return new Promise((resolve, reject) => {
        axios.post('/user/password', data).then((response) => {
          resolve(response)
        }).catch((err) => {
          reject(err)
        })
      })
    },
    updateCurrentProfile({ commit, dispatch, state }, data) {
      return new Promise((resolve, reject) => {
        axios.post(`/profile/${data.id}`, data).then((response) => {
          commit('SET_CURRENT_USER', response.data)
          resolve(response)
        }).catch((err) => {
          reject(err)
        })
      })
    },
    invite({ commit, dispatch, state }, data) {
      return axios.post('/profile/invite', data)
    }
  }
}
