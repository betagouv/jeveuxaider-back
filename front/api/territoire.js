export default (axios) => ({
  async fetchTerritoireAvailableMissions(id, params) {
    return await axios.get(`/territoire/${id}/availableMissions`, {
      params,
    })
  },

  async getCollectivityCities(id, params) {
    return await axios.get(`/territoire/${id}/cities`, {
      params,
    })
  },
})
