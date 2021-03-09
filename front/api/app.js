export default (axios) => ({
  async statistics(name, params) {
    return await axios.get(`/statistics/${name}`, { params })
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
  async statisticsCollectivities(params) {
    return await axios.get(`/statistics/collectivities`, { params })
  },
  async statisticsDomaines(params) {
    return await axios.get(`/statistics/domaines`, { params })
  },
  async fetchCollectivities(params) {
    return await axios.get(`/collectivities`, { params })
  },
  async addCollectivity(collectivity) {
    return await axios.post('/collectivity', collectivity)
  },
  async updateCollectivity(id, collectivity) {
    return await axios.post(`/collectivity/${id}`, collectivity)
  },
  async getCollectivity(id) {
    const { data } = await axios.get(`/collectivity/${id}`)
    return data
  },
  async addOrUpdateCollectivity(id, collectivity) {
    return id
      ? await axios.post(`/collectivity/${id}`, collectivity)
      : await axios.post('/collectivity', collectivity)
  },
  async deleteCollectivity(id) {
    return await axios.delete(`/collectivity/${id}`)
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
})

// export function bootstrap() {
//   return axios.get('/bootstrap')
// }

// export function reminders() {
//   return axios.get('/reminders')
// }

// export function chartCreated(params) {
//   return axios.get('/charts/created', { params })
// }

// export function fetchTrashItems(type, params) {
//   return axios.get(`/trash/${type}`, { params })
// }

// export function fetchFaqs(params) {
//   return axios.get('/faqs', { params })
// }

// export function getFaq(id) {
//   return axios.get(`/faq/${id}`)
// }

// export function addFaq(faq) {
//   return axios.post(`/faq`, faq)
// }

// export function updateFaq(id, faq) {
//   return axios.post(`/faq/${id}`, faq)
// }

// export function deleteFaq(id) {
//   return axios.delete(`/faq/${id}`)
// }

// export function fetchReleases(params) {
//   return axios.get('/releases', { params })
// }

// export function getRelease(id) {
//   return axios.get(`/release/${id}`)
// }

// export function addRelease(release) {
//   return axios.post(`/release`, release)
// }

// export function updateRelease(id, release) {
//   return axios.post(`/release/${id}`, release)
// }

// export function deleteRelease(id) {
//   return axios.delete(`/release/${id}`)
// }

// export function fetchPages(params) {
//   return axios.get('/pages', { params })
// }

// export function getPage(id) {
//   return axios.get(`/page/${id}`)
// }

// export function addPage(page) {
//   return axios.post(`/page`, page)
// }

// export function updatePage(id, page) {
//   return axios.post(`/page/${id}`, page)
// }

// export function deletePage(id) {
//   return axios.delete(`/page/${id}`)
// }

// export function exportTable(table) {
//   return axios.post(`/${table}/export/table`)
// }

// // export function submitCollectivity(collectivity) {
// //   return axios.post("/submit/collectivity", collectivity);
// // }

// export function getCollectivityStatistics(id) {
//   return axios.get(`/collectivity/${id}/statistics`)
// }

// export function fetchAllCollectivities(params) {
//   return axios.get('/collectivities/all', { params })
// }

// export function fetchDepartments(params) {
//   return axios.get('/departments', { params })
// }

// export function deleteCollectivity(id) {
//   return axios.delete(`/collectivity/${id}`)
// }

// export function destroyCollectivity(id) {
//   return axios.delete(`/collectivity/${id}/destroy`)
// }

// export function addDocument(document) {
//   return axios.post('/document', document)
// }

// export function updateDocument(id, document) {
//   return axios.post(`/document/${id}`, document)
// }

// export function notifyDocument(id) {
//   return axios.post(`/document/${id}/notify/`)
// }

// export function addOrUpdateDocument(id, document) {
//   return id ? updateDocument(id, document) : addDocument(document)
// }

// export function getDocument(id) {
//   return axios.get(`/document/${id}`)
// }

// export function fetchDocuments(params) {
//   return axios.get('/documents', { params })
// }

// export function deleteDocument(id) {
//   return axios.delete(`/document/${id}`)
// }

// export function uploadFile(id, model, file) {
//   const data = new FormData()
//   data.append('file', file)
//   return axios.post(`/${model}/${id}/upload`, data, {
//     'Content-Type': 'multipart/form-data',
//   })
// }

// export function deleteFile(id, model) {
//   return axios.delete(`/${model}/${id}/upload`)
// }

// export function updateThematique(id, thematique) {
//   return axios.post(`/thematique/${id}`, thematique)
// }

// export function addOrUpdateThematique(id, thematique) {
//   return id ? updateThematique(id, thematique) : addThematique(thematique)
// }

// export function addThematique(thematique) {
//   return axios.post('/thematique', thematique)
// }

// export function getThematique(id) {
//   return axios.get(`/thematique/${id}`)
// }

// export function getThematiqueStatistics(id) {
//   return axios.get(`/thematique/${id}/statistics`)
// }

// export function fetchThematiques(params) {
//   return axios.get('/thematiques', { params })
// }

// export function deleteThematique(id) {
//   return axios.delete(`/thematique/${id}`)
// }

// export function updateMissionTemplate(id, missionTemplate) {
//   return axios.post(`/mission-template/${id}`, missionTemplate)
// }

// export function addOrUpdateMissionTemplate(id, missionTemplate) {
//   return id
//     ? updateMissionTemplate(id, missionTemplate)
//     : addMissionTemplate(missionTemplate)
// }

// export function addMissionTemplate(missionTemplate) {
//   return axios.post('/mission-template', missionTemplate)
// }

// export function getMissionTemplate(id) {
//   return axios.get(`/mission-template/${id}`)
// }

// export function fetchMissionTemplates(params) {
//   return axios.get('/mission-templates', { params })
// }

// export function deleteMissionTemplate(id) {
//   return axios.delete(`/mission-template/${id}`)
// }

// export function updateTag(id, tag) {
//   return axios.post(`/tag/${id}`, tag)
// }

// export function addOrUpdateTag(id, tag) {
//   return id ? updateTag(id, tag) : addTag(tag)
// }

// export function addTag(tag) {
//   return axios.post('/tag', tag)
// }

// export function getTag(id) {
//   return axios.get(`/tag/${id}`)
// }

// export function deleteTag(id) {
//   return axios.delete(`/tag/${id}`)
// }
