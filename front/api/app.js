import axios from 'axios'

// export function bootstrap() {
//   return axios.get('/api/bootstrap')
// }

export function statistics(name, params) {
  return axios.get(`/api/statistics/${name}`, { params })
}

// export function statisticsCollectivities(params) {
//   return axios.get(`/api/statistics/collectivities`, { params })
// }

// export function statisticsDepartments(params) {
//   return axios.get(`/api/statistics/departments`, { params })
// }

// export function statisticsDomaines(params) {
//   return axios.get(`/api/statistics/domaines`, { params })
// }

// export function exportStatistics(name, params) {
//   return axios.get(`/api/statistics/${name}?type=export`, { params })
// }

// export function reminders() {
//   return axios.get('/api/reminders')
// }

// export function chartCreated(params) {
//   return axios.get('/api/charts/created', { params })
// }

// export function fetchTrashItems(type, params) {
//   return axios.get(`/api/trash/${type}`, { params })
// }

// export function fetchFaqs(params) {
//   return axios.get('/api/faqs', { params })
// }

// export function getFaq(id) {
//   return axios.get(`/api/faq/${id}`)
// }

// export function addFaq(faq) {
//   return axios.post(`/api/faq`, faq)
// }

// export function updateFaq(id, faq) {
//   return axios.post(`/api/faq/${id}`, faq)
// }

// export function deleteFaq(id) {
//   return axios.delete(`/api/faq/${id}`)
// }

// export function fetchReleases(params) {
//   return axios.get('/api/releases', { params })
// }

// export function getRelease(id) {
//   return axios.get(`/api/release/${id}`)
// }

// export function addRelease(release) {
//   return axios.post(`/api/release`, release)
// }

// export function updateRelease(id, release) {
//   return axios.post(`/api/release/${id}`, release)
// }

// export function deleteRelease(id) {
//   return axios.delete(`/api/release/${id}`)
// }

// export function fetchPages(params) {
//   return axios.get('/api/pages', { params })
// }

// export function getPage(id) {
//   return axios.get(`/api/page/${id}`)
// }

// export function addPage(page) {
//   return axios.post(`/api/page`, page)
// }

// export function updatePage(id, page) {
//   return axios.post(`/api/page/${id}`, page)
// }

// export function deletePage(id) {
//   return axios.delete(`/api/page/${id}`)
// }

// export function exportTable(table) {
//   return axios.post(`/api/${table}/export/table`)
// }

// export function addCollectivity(collectivity) {
//   return axios.post('/api/collectivity', collectivity)
// }

// // export function submitCollectivity(collectivity) {
// //   return axios.post("/api/submit/collectivity", collectivity);
// // }

// export function updateCollectivity(id, collectivity) {
//   return axios.post(`/api/collectivity/${id}`, collectivity)
// }

// export function addOrUpdateCollectivity(id, collectivity) {
//   return id
//     ? updateCollectivity(id, collectivity)
//     : addCollectivity(collectivity)
// }

// export function getCollectivity(id) {
//   return axios.get(`/api/collectivity/${id}`)
// }

// export function getCollectivityStatistics(id) {
//   return axios.get(`/api/collectivity/${id}/statistics`)
// }

// export function fetchCollectivities(params) {
//   return axios.get('/api/collectivities', { params })
// }

// export function fetchAllCollectivities(params) {
//   return axios.get('/api/collectivities/all', { params })
// }

// export function fetchDepartments(params) {
//   return axios.get('/api/departments', { params })
// }

// export function deleteCollectivity(id) {
//   return axios.delete(`/api/collectivity/${id}`)
// }

// export function destroyCollectivity(id) {
//   return axios.delete(`/api/collectivity/${id}/destroy`)
// }

// export function uploadImage(id, model, image, cropSettings, fieldName = null) {
//   const data = new FormData()
//   data.append('image', image)
//   data.append('cropSettings', JSON.stringify(cropSettings))
//   return fieldName
//     ? axios.post(`/api/${model}/${id}/upload/${fieldName}`, data, {
//         'Content-Type': 'multipart/form-data',
//       })
//     : axios.post(`/api/${model}/${id}/upload`, data, {
//         'Content-Type': 'multipart/form-data',
//       })
// }

// export function deleteImage(id, model, fieldName = null) {
//   return fieldName
//     ? axios.delete(`/api/${model}/${id}/upload/${fieldName}`)
//     : axios.delete(`/api/${model}/${id}/upload`)
// }

// export function addDocument(document) {
//   return axios.post('/api/document', document)
// }

// export function updateDocument(id, document) {
//   return axios.post(`/api/document/${id}`, document)
// }

// export function notifyDocument(id) {
//   return axios.post(`/api/document/${id}/notify/`)
// }

// export function addOrUpdateDocument(id, document) {
//   return id ? updateDocument(id, document) : addDocument(document)
// }

// export function getDocument(id) {
//   return axios.get(`/api/document/${id}`)
// }

// export function fetchDocuments(params) {
//   return axios.get('/api/documents', { params })
// }

// export function deleteDocument(id) {
//   return axios.delete(`/api/document/${id}`)
// }

// export function uploadFile(id, model, file) {
//   const data = new FormData()
//   data.append('file', file)
//   return axios.post(`/api/${model}/${id}/upload`, data, {
//     'Content-Type': 'multipart/form-data',
//   })
// }

// export function deleteFile(id, model) {
//   return axios.delete(`/api/${model}/${id}/upload`)
// }

// export function updateThematique(id, thematique) {
//   return axios.post(`/api/thematique/${id}`, thematique)
// }

// export function addOrUpdateThematique(id, thematique) {
//   return id ? updateThematique(id, thematique) : addThematique(thematique)
// }

// export function addThematique(thematique) {
//   return axios.post('/api/thematique', thematique)
// }

// export function getThematique(id) {
//   return axios.get(`/api/thematique/${id}`)
// }

// export function getThematiqueStatistics(id) {
//   return axios.get(`/api/thematique/${id}/statistics`)
// }

// export function fetchThematiques(params) {
//   return axios.get('/api/thematiques', { params })
// }

// export function deleteThematique(id) {
//   return axios.delete(`/api/thematique/${id}`)
// }

// export function updateMissionTemplate(id, missionTemplate) {
//   return axios.post(`/api/mission-template/${id}`, missionTemplate)
// }

// export function addOrUpdateMissionTemplate(id, missionTemplate) {
//   return id
//     ? updateMissionTemplate(id, missionTemplate)
//     : addMissionTemplate(missionTemplate)
// }

// export function addMissionTemplate(missionTemplate) {
//   return axios.post('/api/mission-template', missionTemplate)
// }

// export function getMissionTemplate(id) {
//   return axios.get(`/api/mission-template/${id}`)
// }

// export function fetchMissionTemplates(params) {
//   return axios.get('/api/mission-templates', { params })
// }

// export function deleteMissionTemplate(id) {
//   return axios.delete(`/api/mission-template/${id}`)
// }

// export function updateTag(id, tag) {
//   return axios.post(`/api/tag/${id}`, tag)
// }

// export function addOrUpdateTag(id, tag) {
//   return id ? updateTag(id, tag) : addTag(tag)
// }

// export function addTag(tag) {
//   return axios.post('/api/tag', tag)
// }

// export function getTag(id) {
//   return axios.get(`/api/tag/${id}`)
// }

// export function fetchTags(params) {
//   return axios.get('/api/tags', { params })
// }

// export function deleteTag(id) {
//   return axios.delete(`/api/tag/${id}`)
// }

// export function fetchActivities(params) {
//   return axios.get('/api/activities', { params })
// }
