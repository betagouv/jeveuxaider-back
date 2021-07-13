export default (axios, config) => ({
  async apiEngagementMyMission(id) {
    return await axios.get(`/apiengagement/mymission/${id}`)
  },
})
