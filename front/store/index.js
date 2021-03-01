import { rolesList } from '../api/user'

export const state = () => ({
  searchOverlay: false,
})

export const getters = {
  contextRole: (state) => state.user && state.user.user.context_role,
  hasRoles: (state, getters) => {
    return (
      getters.profile &&
      rolesList.filter((role) => getters.profile.roles[role.key] === true)
    )
  },
  isLogged: (state) => !!(state.auth.accessToken && state.auth.user),
  profile: (state) => (state.user ? state.user.user.profile : null),
  reminders: (state) => state.reminders,
  searchOverlay: (state) => state.searchOverlay,
  user: (state) => state.auth.user,
}

export const mutations = {
  toggleSearchOverlay: (state) => {
    state.searchOverlay = !state.searchOverlay
  },
}

export const actions = {
  async nuxtServerInit({ commit }, { store }) {
    console.log('nuxtServerInit')
    await store.dispatch('bootstrap')
    if (this.$cookies.get('access-token')) {
      commit('auth/setAccessToken', this.$cookies.get('access-token'))
      await store.dispatch('auth/fetchUser')
    }
  },
  async bootstrap({ commit }) {
    await this.$axios.get('/bootstrap')
    // commit('setTaxonomies', data.taxonomies)
  },
}
