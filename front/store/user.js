export const actions = {
  async updateProfile({ dispatch }, payload) {
    const profile = await this.$api.updateProfile(payload.id, payload)
    if (profile) {
      dispatch('auth/fetchUser', profile, { root: true })
    }
    return profile
  },
}
