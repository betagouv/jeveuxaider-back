import request from "../utils/request";

export function fetchMissions(params) {
  return request.get("/api/missions", { params });
}

export function fetchYoungMissions(id, params) {
  return request.get(`/api/young/${id}/missions`, { params });
}

export function exportMissions(params) {
  return request.get(`/api/missions/export`, {
    responseType: "blob",
    params
  });
}

export function addMission(structureId, mission) {
  return request.post(`/api/structure/${structureId}/missions`, mission);
}

export function updateMission(id, mission) {
  return request.post(`/api/mission/${id}`, mission);
}

export function cloneMission(id) {
  return request.post(`/api/mission/${id}/clone`);
}

export function deleteMission(id) {
  return request.delete(`/api/mission/${id}`);
}

export function destroyMission(id) {
  return request.delete(`/api/mission/${id}/destroy`);
}

export function getMission(id) {
  return request.get(`/api/mission/${id}`);
}
