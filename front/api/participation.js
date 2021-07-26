export default (axios) => ({
  async addParticipation(missionId, profileId, content) {
    const { data } = await axios.post(`/participation`, {
      mission_id: missionId,
      profile_id: profileId,
      state: 'En attente de validation',
      content,
    })
    return data
  },
  async fetchParticipations(params) {
    return await axios.get('/participations', { params })
  },
  async updateParticipation(id, participation) {
    return await axios.post(`/participation/${id}`, participation)
  },
  async declineParticipation(id, participation) {
    return await axios.post(`/participation/${id}/decline`, participation)
  },
  async cancelParticipation(id, participation) {
    return await axios.post(`/participation/${id}/cancel`, participation)
  },
  async exportParticipations(params) {
    return await axios.get(`/participations/export`, {
      responseType: 'blob',
      params,
    })
  },
  async getParticipation(id) {
    const { data } = await axios.get(`/participation/${id}`)
    return data
  },
  async getParticipationConversation(id) {
    const { data } = await axios.get(`/participation/${id}/conversation`)
    return data
  },
  async getParticipationBenevole(id) {
    const { data } = await axios.get(`/participation/${id}/benevole`)
    return data
  },
  async deleteParticipation(id) {
    return await axios.delete(`/participation/${id}`)
  },
  async massValidationParticipation() {
    return await axios.post(`/participations/mass-validation`)
  },
})
