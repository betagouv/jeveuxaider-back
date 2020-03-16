export default {
  namespaced: true,

  state: {
    taxonomies: null,
    activities: null,
    collaborators: null,
  },

  getters: {
    taxonomies: (state) => state.taxonomies,
    activities: (state) => state.activities,
    collaborators: (state) => state.collaborators,
  },

  mutations: {
    SET_TAXONOMIES (state, taxonomies) {
      state.taxonomies = taxonomies
    },
    SET_ACTIVITIES (state, activities) {
      state.activities = activities
    },
    SET_COLLABORATORS (state, collaborators) {
      state.collaborators = collaborators
    },
  },

  actions: {

  }
}
