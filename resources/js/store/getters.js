import dayjs from 'dayjs'
import { rolesList } from '../api/user'

const getters = {
  isAppLoaded: (state) => state.isAppLoaded,
  sidebar: (state) => state.sidebar,
  searchOverlay: (state) => state.searchOverlay,
  isLogged: (state) => !!state.auth.accessToken && state.user.user,
  tokenHasExpired: (state) => {
    return state.auth.dateExpire
      ? dayjs().isAfter(dayjs(state.auth.dateExpire))
      : true
  },
  user: (state) => state.user.user,
  loading: (state) => state.loading,
  taxonomies: (state) => state.taxonomies,
  thematiques: (state) => state.thematiques,
  release: (state) => state.release,
  isImpersonating: (state) =>
    state.auth.accessTokenImpersonate ? true : false,
  reseaux: (state) => state.reseaux,
  collectivities: (state) => state.collectivities,
  reminders: (state) => state.reminders,
  profile: (state) => (state.user.user ? state.user.user.profile : null),
  contextRole: (state) => state.user.user.context_role,
  showAvisBenevole: (state) => state.showAvisBenevole,
  participationsValidated: (state, getters) => {
    return getters.user.profile && getters.user.profile.participations
      ? getters.user.profile.participations.filter((participation) =>
          ['Validée', 'Effectuée'].includes(participation.state)
        ).length
      : 0
  },
  structure_as_responsable: (state, getters) => {
    if (!getters.profile && !getters.profile.structures) {
      return null
    }
    return getters.profile.structures.filter(
      (structure) => structure.pivot.role == 'responsable'
    )[0]
  },
  noRole: (state, getters) => {
    if (!getters.profile) {
      return null
    }
    return Object.values(getters.profile.roles).every((role) => !role)
  },
  hasRoles: (state, getters) => {
    if (!getters.profile) {
      return null
    }
    return rolesList.filter((role) => getters.profile.roles[role.key] === true)
  },
}
export default getters
