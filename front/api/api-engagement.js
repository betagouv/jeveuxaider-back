export default (axios, config) => ({
  async apiEngagementMyMission(id) {
    return await axios.get(
      `https://api.api-engagement.beta.gouv.fr/v0/mymission/${id}`,
      {
        headers: {
          apikey: config.apieng.key,
        },
      }
    )
  },
})
