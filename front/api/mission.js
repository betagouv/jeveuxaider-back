export default (axios) => ({
  async getMission(id) {
    const { data } = await axios.get(`/mission/${id}`)
    return data
  },
  async getMissionResponsable(id) {
    const { data } = await axios.get(`/mission/${id}/responsable`)
    return data
  },
  async fetchMissions(params) {
    return await axios.get('/missions', { params })
  },
  async deleteMission(id) {
    return await axios.delete(`/mission/${id}`)
  },
  async updateMission(id, mission) {
    return await axios.post(`/mission/${id}`, mission)
  },
  async cloneMission(id) {
    return await axios.post(`/mission/${id}/clone`)
  },
  async exportMissions(params) {
    return await axios.get(`/missions/export`, {
      responseType: 'blob',
      params,
    })
  },
  async addStructureMission(structureId, mission) {
    return await axios.post(`/structure/${structureId}/missions`, mission)
  },
  async restoreMission(id) {
    return await axios.post(`/mission/${id}/restore`)
  },
  async destroyMission(id) {
    return await axios.delete(`/mission/${id}/destroy`)
  },
  async similarMission(id, params) {
    const { data } = await axios.get(`/mission/${id}/similar`)
    return data
  },
})

/*

async getMissionStructure(id) {
  return await axios.get(`/mission/${id}/structure`)
}
*/
