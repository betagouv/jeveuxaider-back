import Vue from "vue";
import Vuex from "vuex";
import auth from "./modules/auth";
import user from "./modules/user";
import volet from "./modules/volet";
import getters from "./getters";
import { bootstrap } from "../api/app";

Vue.use(Vuex);

const state = {
  isAppLoaded: false,
  sidebar: true,
  loading: false,
  taxonomies: null,
  reseaux: null,
  release: null
};

// actions
const actions = {
  async bootstrap({ commit, dispatch, getters }) {
    const { data } = await bootstrap();
    commit("setTaxonomies", data.taxonomies);
    commit("setReseaux", data.reseaux);
    if(data.user) {
      commit("user/setUser", data.user);
    }
    commit("setAppLoadingStatus", true);
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
  setReseaux: (state, reseaux) => {
    state.reseaux = reseaux;
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
