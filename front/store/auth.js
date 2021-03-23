export const state = () => ({
  accessToken: null,
  user: null,
  accessTokenImpersonate: null,
  tokenIdImpersonate: null,
})

export const mutations = {
  setAccessToken(state, token) {
    state.accessToken = token
  },
  setAccessTokenImpersonate(state, token) {
    console.log('set impersonate', token)
    state.accessTokenImpersonate = token
  },
  setTokenIdImpersonate(state, tokenId) {
    state.tokenIdImpersonate = tokenId
  },
  setUser(state, user) {
    state.user = user
  },
  decrementNbUnreadConversations(state) {
    if (state.user.nbUnreadConversations > 0) {
      state.user.nbUnreadConversations--
    }
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
          path: '/',
        })
        await dispatch('fetchUser')
        this.$router.push(
          this.$router.history.current.query.redirect || '/missions-benevolat'
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

  registerVolontaire({ dispatch }, user) {
    return new Promise((resolve, reject) => {
      this.$api
        .registerVolontaire(
          user.email,
          user.password,
          user.first_name,
          user.last_name,
          user.mobile,
          user.birthday,
          user.zip,
          user.service_civique
        )
        .then(() => {
          dispatch('login', user).then((response) => {
            resolve(response)
          })
        })
        .catch((error) => {
          if (error.response.data.errors && error.response.data.errors.email) {
            if (
              error.response.data.errors.email ==
              'Cet email est déjà pris. Merci de vous connecter avec vos identifiants.'
            ) {
              this.$router.push('/login?email=' + user.email)
            }
          }
          reject(error)
        })
    })
  },

  registerResponsable({ dispatch }, user) {
    return new Promise((resolve, reject) => {
      this.$api
        .registerResponsable(
          user.email,
          user.password,
          user.first_name,
          user.last_name,
          user.structure_name
        )
        .then(() => {
          dispatch('login', user).then((response) => {
            resolve(response)
          })
        })
        .catch((error) => {
          if (error.response.data.errors && error.response.data.errors.email) {
            if (
              error.response.data.errors.email ==
              'Cet email est déjà pris. Merci de vous connecter avec vos identifiants.'
            ) {
              this.$router.push('/login?email=' + user.email)
            }
          }
          reject(error)
        })
    })
  },

  registerInvitation({ dispatch }, params) {
    return new Promise((resolve, reject) => {
      this.$api
        .registerInvitation(params.form, params.invitation.token)
        .then(() => {
          dispatch('login', params.form).then((response) => {
            resolve(response)
          })
        })
        .catch((error) => {
          reject(error)
        })
    })
  },

  async updateUser({ state, commit }, attributes) {
    const res = await this.$axios.post('/user', {
      ...state.user,
      ...attributes,
    })
    commit('setUser', res ? res.data : null)
  },

  async impersonate({ commit, dispatch }, userId) {
    const { data } = await this.$axios.post(`/impersonate/${userId}`)
    console.log('set token impersonate with response', data)
    commit('setAccessTokenImpersonate', data.accessToken)
    commit('setTokenIdImpersonate', data.token.id)
    this.$cookies.set('access-token-impersonate', data.accessToken, {
      maxAge: 3600, // 1 heure
    })
    this.$cookies.set('token-id-impersonate', data.token.id, {
      maxAge: 3600, // 1 heure
    })
    await dispatch('fetchUser')
    this.$router.push('/')
  },

  async stopImpersonate({ state, commit, dispatch }) {
    console.log('state here', state)
    await this.$axios.delete(`/impersonate/${state.tokenIdImpersonate}`, {
      headers: { Authorization: `Bearer ${state.accessToken}` },
    })
    commit('setAccessTokenImpersonate', null)
    commit('setTokenIdImpersonate', null)
    this.$cookies.remove('access-token-impersonate')
    this.$cookies.remove('token-id-impersonate')
    await dispatch('fetchUser')
    this.$router.push('/')
  },
}
