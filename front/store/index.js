import { rolesList } from '../api/user'

export const state = () => ({})

export const getters = {
  isLogged: (state) => !!(state.auth.accessToken && state.auth.user),
  user: (state) => state.auth.user,
  contextRole: (state) => state.user && state.user.user.context_role,
  hasRoles: (state, getters) => {
    return (
      getters.profile &&
      rolesList.filter((role) => getters.profile.roles[role.key] === true)
    )
  },
  reminders: (state) => state.reminders,
  profile: (state) => (state.user ? state.user.user.profile : null),
}

export const mutations = {}

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
