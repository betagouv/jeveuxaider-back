export default (axios) => ({
  async fetchStructureAvailableMissions(id, params) {
    const { data } = await axios.get(`/structure/${id}/availableMissions`, {
      params,
    })
    return data
  },
  async fetchStructures(params) {
    return await axios.get('/structures', { params })
  },
  async exportStructures(params) {
    return await axios.get('/structures/export', {
      params,
      responseType: 'blob',
    })
  },
  async addStructure(structure) {
    return await axios.post('/structure', structure)
  },
  async updateStructure(id, structure) {
    return await axios.post(`/structure/${id}`, structure)
  },
  async getStructure(id) {
    const { data } = await axios.get(`/structure/${id}`)
    return data
  },
  async addOrUpdateStructure(id, structure) {
    return id
      ? await axios.post(`/structure/${id}`, structure)
      : await axios.post('/structure', structure)
  },
  async deleteStructure(id) {
    return await axios.delete(`/structure/${id}`)
  },
  async destroyStructure(id) {
    return await axios.delete(`/structure/${id}/destroy`)
  },
  async getStructureMembers(id) {
    return await axios.get(`/structure/${id}/members`)
  },
  async inviteStructureMember(id, member) {
    return await axios.post(`/structure/${id}/members`, member)
  },
  async deleteMember(structureId, memberId) {
    return await axios.delete(`/structure/${structureId}/members/${memberId}`)
  },
  async getStructureInvitations(id) {
    return await axios.get(`/structure/${id}/invitations`)
  },
})

/*

async updateStructureLogo(id, logo) {
  var data = new FormData()
  data.append('logo', logo)
  return await axios.post(`/structure/${id}`, data, {
    'Content-Type': 'multipart/form-data',
  })
}



*/
