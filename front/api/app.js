export default (axios) => ({
  async statistics(name, params) {
    return await axios.get(`/statistics/${name}`, { params })
  },
  async statisticsBySubject(type, id, params) {
    return await axios.get(`/statistics/${type}/${id}`, { params })
  },
  async chartCreated(params) {
    return await axios.get('/charts/created', { params })
  },
  async statisticsDepartments(params) {
    return await axios.get('/statistics/departments', { params })
  },
  async exportStatistics(name, params) {
    return await axios.get(`/statistics/${name}?type=export`, { params })
  },
  async statisticsDomaines(params) {
    return await axios.get(`/statistics/domaines`, { params })
  },
  async fetchDepartments(params) {
    return await axios.get('/departments', { params })
  },
  async deleteImage(id, model, fieldName = null) {
    return fieldName
      ? await axios.delete(`/${model}/${id}/upload/${fieldName}`)
      : await axios.delete(`/${model}/${id}/upload`)
  },
  async fetchTags(params) {
    return await axios.get('/tags', { params })
  },
  async fetchActivities(params) {
    return await axios.get('/activities', { params })
  },
  async uploadImage(id, model, image, cropSettings, fieldName = null) {
    const data = new FormData()
    const options = {
      'Content-Type': 'multipart/form-data',
    }
    data.append('image', image)
    data.append('cropSettings', JSON.stringify(cropSettings))
    return fieldName
      ? await axios.post(`/${model}/${id}/upload/${fieldName}`, data, options)
      : await axios.post(`/${model}/${id}/upload`, data, options)
  },
  async fetchMissionTemplates(params) {
    return await axios.get('/mission-templates', { params })
  },
  async fetchReleases(params) {
    return await axios.get('/releases', { params })
  },
  async getRelease(id) {
    const { data } = await axios.get(`/release/${id}`)
    return data
  },
  async addRelease(release) {
    return await axios.post(`/release`, release)
  },
  async updateRelease(id, release) {
    return await axios.post(`/release/${id}`, release)
  },
  async deleteRelease(id) {
    return await axios.delete(`/release/${id}`)
  },
  async fetchPages(params) {
    return await axios.get('/pages', { params })
  },
  async getPage(id) {
    const { data } = await axios.get(`/page/${id}`)
    return data
  },
  async addPage(page) {
    return await axios.post(`/page`, page)
  },
  async updatePage(id, page) {
    return await axios.post(`/page/${id}`, page)
  },
  async deletePage(id) {
    return await axios.delete(`/page/${id}`)
  },
  async addDocument(document) {
    return await axios.post('/document', document)
  },

  async updateDocument(id, document) {
    return await axios.post(`/document/${id}`, document)
  },
  async notifyDocument(id) {
    return await axios.post(`/document/${id}/notify`)
  },
  async addOrUpdateDocument(id, document) {
    return id
      ? await axios.post(`/document/${id}`, document)
      : await axios.post('/document', document)
  },
  async getDocument(id) {
    const { data } = await axios.get(`/document/${id}`)
    return data
  },
  async fetchDocuments(params) {
    return await axios.get('/documents', { params })
  },
  async deleteDocument(id) {
    return await axios.delete(`/document/${id}`)
  },
  async uploadFile(id, model, file) {
    const data = new FormData()
    data.append('file', file)
    return await axios.post(`/${model}/${id}/upload`, data, {
      'Content-Type': 'multipart/form-data',
    })
  },
  async deleteFile(id, model) {
    return await axios.delete(`/${model}/${id}/upload`)
  },
  async updateThematique(id, thematique) {
    return await axios.post(`/thematique/${id}`, thematique)
  },
  async addOrUpdateThematique(id, thematique) {
    return id
      ? await axios.post(`/thematique/${id}`, thematique)
      : await axios.post('/thematique', thematique)
  },
  async addThematique(thematique) {
    return await axios.post('/thematique', thematique)
  },
  async getThematique(id) {
    const { data } = await axios.get(`/thematique/${id}`)
    return data
  },
  async getThematiqueStatistics(id) {
    return await axios.get(`/thematique/${id}/statistics`)
  },
  async fetchThematiques(params) {
    return await axios.get('/thematiques', { params })
  },
  async deleteThematique(id) {
    return await axios.delete(`/thematique/${id}`)
  },
  async updateMissionTemplate(id, missionTemplate) {
    return await axios.post(`/mission-template/${id}`, missionTemplate)
  },
  async addOrUpdateMissionTemplate(id, missionTemplate) {
    return id
      ? await axios.post(`/mission-template/${id}`, missionTemplate)
      : await axios.post('/mission-template', missionTemplate)
  },
  async addMissionTemplate(missionTemplate) {
    return await axios.post('/mission-template', missionTemplate)
  },
  async getMissionTemplate(id) {
    const { data } = await axios.get(`/mission-template/${id}`)
    return data
  },
  async deleteMissionTemplate(id) {
    return await axios.delete(`/mission-template/${id}`)
  },
  async updateTag(id, tag) {
    return await axios.post(`/tag/${id}`, tag)
  },
  async addOrUpdateTag(id, tag) {
    return id
      ? await axios.post(`/tag/${id}`, tag)
      : await axios.post('/tag', tag)
  },
  async addTag(tag) {
    return await axios.post('/tag', tag)
  },
  async getTag(id) {
    const { data } = await axios.get(`/tag/${id}`)
    return data
  },
  async deleteTag(id) {
    return await axios.delete(`/tag/${id}`)
  },
  async fetchTrashItems(type, params) {
    return await axios.get(`/trash/${type}`, { params })
  },
  async fetchTopitoBenevolesDuMoment(params) {
    return await axios.get('/topito/benevoles-du-moment', { params })
  },
  async fetchTopitoUtilisateursLesPlusActifs(params) {
    return await axios.get('/topito/utilisateurs-les-plus-actifs', { params })
  },
  async fetchTopitoOrganisationsMissions(params) {
    return await axios.get('/topito/organisations-missions', { params })
  },
  async fetchTopitoOrganisationsParticipations(params) {
    return await axios.get('/topito/organisations-participations', { params })
  },
})

// async bootstrap() {
//   return await axios.get('/bootstrap')
// }

// async reminders() {
//   return await axios.get('/reminders')
// }

// async chartCreated(params) {
//   return await axios.get('/charts/created', { params })
// }

// async getFaq(id) {
//   return await axios.get(`/faq/${id}`)
// }

// async addFaq(faq) {
//   return await axios.post(`/faq`, faq)
// }

// async updateFaq(id, faq) {
//   return await axios.post(`/faq/${id}`, faq)
// }

// async deleteFaq(id) {
//   return await axios.delete(`/faq/${id}`)
// }

// async exportTable(table) {
//   return await axios.post(`/${table}/export/table`)
// }
