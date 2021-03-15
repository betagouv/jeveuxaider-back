export const state = () => ({
  accessToken: null,
  user: null,
})

export const mutations = {
  setAccessToken(state, token) {
    state.accessToken = token
  },
  setUser(state, user) {
    state.user = user
  },
}

export const actions = {
  login({ commit, dispatch }, credentials) {
    return this.$axios
      .post(`${this.$config.apiUrl}/oauth/token`, {
        grant_type: 'password',
        client_id: this.$config.oauth.clientId,
        client_secret: this.$config.oauth.clientSecret,
        username: credentials.email.toLowerCase(),
        password: credentials.password,
        scope: '*',
      })
      .then(async ({ data }) => {
        commit('setAccessToken', data.access_token)
        this.$cookies.set('access-token', data.access_token, {
          maxAge: data.expires_in,
        })
        await dispatch('fetchUser')
        this.$router.push(
          this.$router.history.current.query.redirect || '/missions'
        )
      })
      .catch(() => {
        commit('setAccessToken', null)
        this.$cookies.remove('access-token')
      })
  },
  async logout({ commit }) {
    await this.$axios.post('/logout')
    commit('setAccessToken', null)
    this.$cookies.remove('access-token')
    this.$router.push('/')
  },
  async fetchUser({ commit }) {
    const res = await this.$axios
      .get('/user')
      .catch(() => this.$cookies.remove('access-token'))
    commit('setUser', res ? res.data : null)
  },
  async updateUser({ state, commit }, attributes) {
    const res = await this.$axios.post('/user', {
      ...state.user,
      ...attributes,
    })
    console.log('updateUser', { ...state.user, ...attributes })
    console.log('setUser', res.data)
    commit('setUser', res ? res.data : null)
  },
}
