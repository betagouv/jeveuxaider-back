import request from "../utils/request";

export function addCollectivity(collectivity) {
  return request.post("/api/collectivity", collectivity);
}

export function submitCollectivity(collectivity) {
  return request.post("/api/submit/collectivity", collectivity);
}

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

