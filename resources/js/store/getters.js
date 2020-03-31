import dayjs from "dayjs";
import { rolesList } from "../api/user";

const getters = {
  isAppLoaded: state => state.isAppLoaded,
  isLogged: state => !!state.auth.accessToken,
  tokenHasExpired: state => {
    return state.auth.dateExpire
      ? dayjs().isAfter(dayjs(state.auth.dateExpire))
      : true;
  },
  user: state => state.user.user,
  loading: state => state.loading,
  taxonomies: state => state.taxonomies,
  release: state => state.release,
  isImpersonating: state => (state.auth.accessTokenImpersonate ? true : false),
  reseaux: state => state.reseaux,
  profile: state => (state.user.user ? state.user.user.profile : null),
  contextRole: state => state.user.user.context_role,
  structure_as_responsable: (state, getters) => {
    if (!getters.profile) {
      return null;
    }
    return getters.profile.structures.filter(
      structure => structure.pivot.role == "responsable"
    )[0];
  },
  noRole: (state, getters) => {
    if (!getters.profile) {
      return null;
    }
    return Object.values(getters.profile.roles).every(role => !role);
  },
  hasRoles: (state, getters) => {
    if (!getters.profile) {
      return null;
    }
    return rolesList.filter(role => getters.profile.roles[role.key] === true);
  },
  responsableMissions: state => state.responsableMissions
};
export default getters;
