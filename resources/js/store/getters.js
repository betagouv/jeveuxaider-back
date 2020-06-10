import dayjs from 'dayjs'
import { rolesList } from '../api/user'

const getters = {
  isAppLoaded: (state) => state.isAppLoaded,
  sidebar: (state) => state.sidebar,
  isLogged: (state) => !!state.auth.accessToken,
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
  reminders: (state) => state.reminders,
  profile: (state) => (state.user.user ? state.user.user.profile : null),
  contextRole: (state) => state.user.user.context_role,
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
  isVolunteerOnly: (state, getters) => {
    return getters.noRole
  },
  hasRoles: (state, getters) => {
    if (!getters.profile) {
      return null
    }
    return rolesList.filter((role) => getters.profile.roles[role.key] === true)
  },
}
export default getters
