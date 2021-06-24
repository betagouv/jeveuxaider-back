export default (axios) => ({
  async fetchTerritoires(params) {
    return await axios.get(`/territoires`, { params })
  },

  async addTerritoire(territoire) {
    return await axios.post('/territoire', territoire)
  },

  async updateTerritoire(id, territoire) {
    return await axios.post(`/territoire/${id}`, territoire)
  },

  async getTerritoire(id) {
    const { data } = await axios.get(`/territoire/${id}`)
    return data
  },

  async deleteTerritoire(id) {
    return await axios.delete(`/territoire/${id}`)
  },

  async getTerritoireResponsables(id) {
    return await axios.get(`/territoire/${id}/responsables`)
  },

  async getTerritoireInvitations(id) {
    return await axios.get(`/territoire/${id}/invitations`)
  },

  async fetchTerritoireAvailableMissions(id, params) {
    return await axios.get(`/territoire/${id}/availableMissions`, {
      params,
    })
  },

  async getCitiesWithAvailableMissions(id, params) {
    return await axios.get(`/territoire/${id}/cities`, {
      params,
    })
  },
})
