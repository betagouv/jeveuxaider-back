import { getUser, updateUser, updateProfile, rolesList } from '../../api/user'

const state = {
  user: {},
}

// getters
const getters = {
  contextRoleLabel: (state) => {
    const rolesLabel = rolesList.filter(
      (role) => role.key == state.user.context_role
    )
    if (rolesLabel.length > 0) {
      return rolesLabel[0].label
    }
    return 'Aucun rôle'
  },
}

// mutations
const mutations = {
  setUser: (state, user) => {
    state.user = user
  },
  deleteUser: (state) => {
    state.user = {}
  },
  updateProfile: (state, profile) => {
    state.user.profile = profile
  },
}

// actions
const actions = {
  async get({ commit }) {
    const response = await getUser()
    commit('setUser', response.data)
    return response
  },
  async update({ commit }, user) {
    const response = await updateUser(user)
    commit('setUser', response.data)
  },
  async setContextRole({ state, dispatch, commit }, context_role) {
    commit('setLoading', true, { root: true })
    await dispatch('update', { ...state.user, context_role })
  },
  updateProfile({ commit }, profile) {
    return new Promise((resolve, reject) => {
      updateProfile(profile.id, profile)
        .then((response) => {
          commit('updateProfile', response.data)
          resolve(response)
        })
        .catch((error) => {
          reject(error)
        })
    })
  },
}

export default {
  namespaced: true,
  getters,
  state,
  actions,
  mutations,
}
