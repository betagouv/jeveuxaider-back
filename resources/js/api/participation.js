import request from "../utils/request";

export function fetchParticipations(params) {
  return request.get("/api/participations", { params });
}

// export function fetchYoungMissions(id, params) {
//   return request.get(`/api/young/${id}/missions`, { params });
// }

export function exportParticipations(params) {
  return request.get(`/api/participations/export`, {
    responseType: "blob",
    params
  });
}

// export function addParticipation(structureId, participation) {
//   return request.post(`/api/structure/${structureId}/participations`, participation);
// }

export function updateParticipation(id, participation) {
  return request.post(`/api/participations/${id}`, participation);
}

export function deleteParticipation(id) {
  return request.delete(`/api/participation/${id}`);
}

export function destroyParticipation(id) {
  return request.delete(`/api/participation/${id}/destroy`);
}

// export function getParticipation(id) {
//   return request.get(`/api/participation/${id}`);
// }
