import request from "../utils/request";

export function fetchParticipations(params) {
  return request.get("/api/participations", { params });
}

export function exportParticipations(params) {
  return request.get(`/api/participations/export`, {
    responseType: "blob",
    params
  });
}

export function addParticipation(mission_id, profile_id) {
  return request.post(`/api/participation`, {
    mission_id,
    profile_id,
    state: 'En attente de validation'
  });
}

export function updateParticipation(id, participation) {
  return request.post(`/api/participation/${id}`, participation);
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
