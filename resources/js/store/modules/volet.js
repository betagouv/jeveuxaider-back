const state = {
  row: null,
  active: false,
}

const getters = {
  row: (state) => state.row,
  active: (state) => state.active,
}

// mutations
const mutations = {
  show: (state, row) => {
    state.row = row
    state.active = true
  },
  hide: (state) => {
    state.row = null
    state.active = false
  },
  setRow: (state, row) => {
    state.row = row
  },
}

export default {
  namespaced: true,
  getters,
  state,
  mutations,
}
