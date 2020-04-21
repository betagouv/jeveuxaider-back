import request from "../utils/request";

export function bootstrap() {
  return request.get("/api/bootstrap");
}

export function statistics(name) {
  return request.get(`/api/statistics/${name}`);
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

export function uploadImage(id, model, image) {
  var data = new FormData();
  data.append("image", image);
  return request.post(`/api/${model}/${id}/upload`, data, {
    "Content-Type": "multipart/form-data"
  });
}
