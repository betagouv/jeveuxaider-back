export default (axios) => ({
  async statisticsMission(id, params) {
    return await this.$axios.post(
      `https://api.api-engagement.beta.gouv.fr/v0/mymission/${id}`,
      { params },
      {
        headers: {
          apikey: this.$config.apieng.key,
        },
      }
    )
  },
})
