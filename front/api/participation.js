export default (axios) => ({
  async addParticipation(missionId, profileId, content) {
    const { data } = await axios.post(`/participation`, {
      missionId,
      profileId,
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
  async cancelParticipation(id) {
    return await axios.post(`/participation/${id}/cancel`)
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
  // async deleteParticipation(id) {
  //   return await axios.delete(`/participation/${id}`)
  // }
})
/*

import request from '../utils/request'



async massValidationParticipation() {
  return await axios.post(`/participations/mass-validation`)
}

async addParticipation(mission_id, profile_id, content) {
  return await axios.post(`/participation`, {
    mission_id,
    profile_id,
    state: 'En attente de validation',
    content,
  })
}

*/
