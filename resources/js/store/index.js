import Vue from "vue";
import Vuex from "vuex";
import auth from "./modules/auth";
import user from "./modules/user";
import volet from "./modules/volet";
import getters from "./getters";
import { bootstrap, reminders } from "../api/app";

Vue.use(Vuex);

const state = {
  isAppLoaded: false,
  sidebar: true,
  loading: false,
  taxonomies: null,
  thematiques: null,
  reseaux: null,
  release: null,
  reminders: null
};

// actions
const actions = {
  async bootstrap({ commit, dispatch, getters }) {
    const { data } = await bootstrap();
    commit("setTaxonomies", data.taxonomies);
    commit("setReseaux", data.reseaux);
    commit("setThematiques", data.thematiques);
    if(data.user) {
      commit("user/setUser", data.user);
    } else {
      // Access token plus valide
      commit("auth/deleteTokens");
      commit("user/deleteUser", null, { root: true });
    }
    commit("setAppLoadingStatus", true);
    return data;
  },
  async reminders({ commit }) {
    const { data } = await reminders();
    commit("setReminders", data);
    return data;
  }
};

// mutations
const mutations = {
  setAppLoadingStatus(state, isAppLoaded) {
    state.isAppLoaded = isAppLoaded;
  },
  setTaxonomies: (state, taxonomies) => {
    state.taxonomies = taxonomies;
  },
  setThematiques: (state, thematiques) => {
    state.thematiques = thematiques;
  },
  setReseaux: (state, reseaux) => {
    state.reseaux = reseaux;
  },
  setReminders: (state, reminders) => {
    state.reminders = reminders;
  },
  setRelease: (state, release) => {
    state.release = release;
  },
  setLoading: (state, loading) => {
    state.loading = loading;
  },
  toggleSidebar: (state) => {
    state.sidebar = !state.sidebar
  }
};

export default new Vuex.Store({
  modules: {
    auth,
    user,
    volet
  },
  state,
  getters,
  mutations,
  actions
});
