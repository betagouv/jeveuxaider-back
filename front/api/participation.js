export default (axios) => ({
  async addParticipation(missionId, profileId, content) {
    const { data } = await axios.post(`/api/participation`, {
      missionId,
      profileId,
      state: 'En attente de validation',
      content,
    })
    return data
  },
})
/*

import request from '../utils/request'

export function getParticipation(id) {
  return request.get(`/api/participation/${id}`)
}

export function fetchParticipations(params) {
  return request.get('/api/participations', { params })
}

export function exportParticipations(params) {
  return request.get(`/api/participations/export`, {
    responseType: 'blob',
    params,
  })
}

export function massValidationParticipation() {
  return request.post(`/api/participations/mass-validation`)
}

export function addParticipation(mission_id, profile_id, content) {
  return request.post(`/api/participation`, {
    mission_id,
    profile_id,
    state: 'En attente de validation',
    content,
  })
}

export function updateParticipation(id, participation) {
  return request.post(`/api/participation/${id}`, participation)
}

export function declineParticipation(id, participation) {
  return request.post(`/api/participation/${id}/decline`, participation)
}

export function cancelParticipation(id) {
  return request.post(`/api/participation/${id}/cancel`)
}

export function deleteParticipation(id) {
  return request.delete(`/api/participation/${id}`)
}

export function destroyParticipation(id) {
  return request.delete(`/api/participation/${id}/destroy`)
}


*/
