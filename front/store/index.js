import { rolesList } from '@/api/user'

export const state = () => ({
  isAppLoaded: false,
  isSidebarExpanded: true,
  searchOverlay: false,
  softGateOverlay: false,
  missionSelected: null,
  taxonomies: null,
  reseaux: null,
  reminders: null,
  stucture: null,
})

export const getters = {
  isAppLoaded: (state) => state.isAppLoaded,
  isSidebarExpanded: (state) => state.isSidebarExpanded,
  contextRole: (state, getters) =>
    getters.user ? getters.user.context_role : null,
  contextableType: (state, getters) =>
    getters.user ? getters.user.contextable_type : null,
  contextRoleLabel: (state, getters) => {
    const rolesLabel = rolesList.filter(
      (role) => role.key == getters.contextRole
    )
    if (rolesLabel.length > 0) {
      return rolesLabel[0].label
    }
    return 'Aucun rôle'
  },
  contextStructure: (state, getters) => {
    if (state.auth.user.contextable_type == 'territoire') {
      return getters.profile.territoires.find(
        (territoire) => territoire.id == state.auth.user.contextable_id
      )
    }
    if (state.auth.user.contextable_type == 'structure') {
      return getters.profile.structures.find(
        (structure) => structure.id == state.auth.user.contextable_id
      )
    }
  },
  roles: (state, getters) => {
    return (
      getters.profile &&
      rolesList.filter((role) => getters.profile.roles[role.key] === true)
    )
  },
  isLogged: (state) => !!(state.auth.accessToken && state.auth.user),
  profile: (state) => (state.auth.user ? state.auth.user.profile : null),
  reminders: (state) => state.reminders,
  searchOverlay: (state) => state.searchOverlay,
  softGateOverlay: (state) => state.softGateOverlay,
  missionSelected: (state) => state.missionSelected,
  user: (state) => state.auth.user,
  isImpersonating: (state) => !!state.auth.accessTokenImpersonate,
  taxonomies: (state) => state.taxonomies,
  reseaux: (state) => state.reseaux,
  structure: (state, getters) => {
    if (!getters.profile && !getters.profile.structures) {
      return null
    }
    return getters.profile.structures.filter(
      (structure) => structure.pivot.role == 'responsable'
    )[0]
  },
}

export const mutations = {
  setAppIsLoaded(state, value) {
    state.isAppLoaded = value
  },
  setTaxonomies: (state, taxonomies) => {
    state.taxonomies = taxonomies
  },
  setReseaux: (state, reseaux) => {
    state.reseaux = reseaux
  },
  setReminders: (state, reminders) => {
    state.reminders = reminders
  },
  toggleSearchOverlay: (state) => {
    state.searchOverlay = !state.searchOverlay
  },
  toggleSoftGateOverlay: (state) => {
    state.softGateOverlay = !state.softGateOverlay
  },
  setMissionSelected: (state, mission) => {
    state.missionSelected = mission
  },
  toggleSidebar: (state) => {
    state.isSidebarExpanded = !state.isSidebarExpanded
  },
}

export const actions = {
  async nuxtServerInit({ commit }, { store }) {
    // console.log('nuxtServerInit')
    await store.dispatch('bootstrap')
    commit(
      'auth/setAccessTokenImpersonate',
      this.$cookies.get('access-token-impersonate')
    )
    commit(
      'auth/setTokenIdImpersonate',
      this.$cookies.get('token-id-impersonate')
    )
    if (this.$cookies.get('access-token')) {
      commit('auth/setAccessToken', this.$cookies.get('access-token'))
      await store.dispatch('auth/fetchUser')
    }
  },
  async bootstrap({ commit }) {
    const { data } = await this.$axios.get('/bootstrap')
    commit('setTaxonomies', data.taxonomies)
    commit('setReseaux', data.reseaux)
    commit('setAppIsLoaded', true)
  },
  async reminders({ commit }) {
    const { data } = await this.$axios.get('/reminders')
    commit('setReminders', data)
    return data
  },
}
