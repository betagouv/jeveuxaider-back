import request from "../utils/request";

export function bootstrap() {
  return request.get("/api/bootstrap");
}

export function statistics(name, params) {
  return request.get(`/api/statistics/${name}`, { params });
}

export function reminders() {
  return request.get('/api/reminders');
}

export function chartCreated(params) {
  return request.get('/api/charts/created', { params });
}

export function fetchTrashItems(params) {
  return request.get("/api/trash", { params });
}

export function fetchFaqs(params) {
  return request.get("/api/faqs", { params });
}

export function getFaq(id) {
  return request.get(`/api/faq/${id}`);
}

export function addFaq(faq) {
  return request.post(`/api/faq`, faq);
}

export function updateFaq(id, faq) {
  return request.post(`/api/faq/${id}`, faq);
}

export function deleteFaq(id) {
  return request.delete(`/api/faq/${id}`);
}

export function fetchReleases(params) {
  return request.get("/api/releases", { params });
}

export function getRelease(id) {
  return request.get(`/api/release/${id}`);
}

export function addRelease(release) {
  return request.post(`/api/release`, release);
}

export function updateRelease(id, release) {
  return request.post(`/api/release/${id}`, release);
}

export function deleteRelease(id) {
  return request.delete(`/api/release/${id}`);
}

export function fetchPages(params) {
  return request.get("/api/pages", { params });
}

export function getPage(id) {
  return request.get(`/api/page/${id}`);
}

export function addPage(page) {
  return request.post(`/api/page`, page);
}

export function updatePage(id, page) {
  return request.post(`/api/page/${id}`, page);
}

export function deletePage(id) {
  return request.delete(`/api/page/${id}`);
}

export function exportTable(table) {
  return request.post(`/api/${table}/export/table`);
}

export function addCollectivity(collectivity) {
  return request.post("/api/collectivity", collectivity);
}

// export function submitCollectivity(collectivity) {
//   return request.post("/api/submit/collectivity", collectivity);
// }

export function updateCollectivity(id, collectivity) {
  return request.post(`/api/collectivity/${id}`, collectivity);
}

export function addOrUpdateCollectivity(id, collectivity) {
  return id ? updateCollectivity(id, collectivity) : addCollectivity(collectivity);
}

export function getCollectivity(id) {
  return request.get(`/api/collectivity/${id}`);
}

export function fetchCollectivities(params) {
  return request.get("/api/collectivities", { params });
}

export function deleteCollectivity(id) {
  return request.delete(`/api/collectivity/${id}`);
}

export function destroyCollectivity(id) {
  return request.delete(`/api/collectivity/${id}/destroy`);
}

export function uploadImage(id, model, image, cropSettings) {
  var data = new FormData();
  data.append("image", image);
  data.append("cropSettings", JSON.stringify(cropSettings))
  return request.post(`/api/${model}/${id}/upload`, data, {
    "Content-Type": "multipart/form-data"
  });
}

export function deleteImage(id, model) {
  return request.delete(`/api/${model}/${id}/upload`);
}

export function addDocument(document) {
  return request.post("/api/document", document);
}

export function updateDocument(id, document) {
  return request.post(`/api/document/${id}`, document);
}

export function addOrUpdateDocument(id, document) {
  return id ? updateDocument(id, document) : addDocument(document);
}

export function getDocument(id) {
  return request.get(`/api/document/${id}`);
}

export function fetchDocuments(params) {
  return request.get("/api/documents", { params });
}

export function deleteDocument(id) {
  return request.delete(`/api/document/${id}`);
}

export function uploadFile(id, model, file) {
  var data = new FormData();
  data.append("file", file);
  return request.post(`/api/${model}/${id}/upload`, data, {
    "Content-Type": "multipart/form-data"
  });
}

export function deleteFile(id, model) {
  return request.delete(`/api/${model}/${id}/upload`);
}

export function updateThematique(id, thematique) {
  return request.post(`/api/thematique/${id}`, thematique);
}

export function addOrUpdateThematique(id, thematique) {
  return id ? updateThematique(id, thematique) : addThematique(thematique);
}

export function addThematique(thematique) {
  return request.post("/api/thematique", thematique);
}

export function getThematique(id) {
  return request.get(`/api/thematique/${id}`);
}

export function fetchThematiques(params) {
  return request.get("/api/thematiques", { params });
}

export function deleteThematique(id) {
  return request.delete(`/api/thematique/${id}`);
}

export function updateMissionTemplate(id, missionTemplate) {
  return request.post(`/api/mission-template/${id}`, missionTemplate);
}

export function addOrUpdateMissionTemplate(id, missionTemplate) {
  return id ? updateMissionTemplate(id, missionTemplate) : addMissionTemplate(missionTemplate);
}

export function addMissionTemplate(missionTemplate) {
  return request.post("/api/mission-template", missionTemplate);
}

export function getMissionTemplate(id) {
  return request.get(`/api/mission-template/${id}`);
}

export function fetchMissionTemplates(params) {
  return request.get("/api/mission-templates", { params });
}

export function deleteMissionTemplate(id) {
  return request.delete(`/api/mission-template/${id}`);
}
