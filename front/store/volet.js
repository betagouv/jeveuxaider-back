export const state = () => ({
  row: null,
  active: false,
})

export const getters = {
  row: (state) => state.row,
  active: (state) => state.active,
}

export const mutations = {
  show: (state, row) => {
    state.row = row
    state.active = true
    // window.$crisp.push(['do', 'chat:hide'])
  },
  hide: (state) => {
    state.row = null
    state.active = false
    // window.$crisp.push(['do', 'chat:show'])
  },
  setRow: (state, row) => {
    state.row = row
  },
}

export const actions = {}
