export default (axios) => ({
  async fetchStructureAvailableMissionsWithPagination(id, params) {
    return await axios.get(`/structure/${id}/availableMissions`, {
      params,
    })
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
  async exportTerritoires(params) {
    return await axios.get('/territoires/export', {
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
  async unsubscribeStructure(id) {
    return await axios.post(`/structure/${id}/unsubscribe`)
  },
  async getStructure(id) {
    const { data } = await axios.get(`/structure/${id}`)
    return data
  },
  async getAssociationBySlugOrId(slugOrId) {
    const { data } = await axios.get(`/association/${slugOrId}`)
    return data
  },
  async addOrUpdateStructure(id, structure) {
    return id
      ? await axios.post(`/structure/${id}`, structure)
      : await axios.post('/structure', structure)
  },
  async addOrUpdateReseau(reseau) {
    return reseau.id
      ? await axios.post(`/reseaux/${reseau.id}`, reseau)
      : await axios.post('/reseaux', reseau)
  },
  async deleteStructure(id) {
    return await axios.delete(`/structure/${id}`)
  },
  async destroyStructure(id) {
    return await axios.delete(`/structure/${id}/destroy`)
  },
  async restoreStructure(id) {
    return await axios.post(`/structure/${id}/restore`)
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
  async sendStructureToApiEngagement(structureId) {
    return await axios.post(`/structure/${structureId}/push-api-engagement`)
  },
  async fetchStructuresWithoutRna(params) {
    return await axios.get('/structures-without-rna', { params })
  },
  async assignStructureRna(structureId, params) {
    return await axios.post(`/structure/${structureId}/rna`, params)
  },
  async structureExists(apiId) {
    return await axios.get(`/structure/${apiId}/exist`)
  },
  async fetchReseaux(params) {
    return await axios.get('/reseaux', { params })
  },
  async reseauLead(form) {
    return await axios.post('/reseaux/lead', form)
  },
  async getReseau(id) {
    const { data } = await axios.get(`/reseaux/${id}`)
    return data
  },
  async addReseauOrga(id, organisations) {
    return await axios.post(`/reseaux/${id}/organisations`, { organisations })
  },
  async deleteReseau(id) {
    return await axios.delete(`/reseaux/${id}`)
  },
})
