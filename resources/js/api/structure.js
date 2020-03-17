import request from "../utils/request";

export function addStructure(structure) {
  return request.post("api/structure", structure);
}
export function updateStructure(id, structure) {
  return request.post(`api/structure/${id}`, structure);
}

export function updateStructureLogo(id, logo) {
  var data = new FormData();
  data.append("logo", logo);
  return request.post(`api/structure/${id}`, data, {
    "Content-Type": "multipart/form-data"
  });
}

export function addOrUpdateStructure(id, structure) {
  return id ? updateStructure(id, structure) : addStructure(structure);
}

export function getStructure(id) {
  return request.get(`api/structure/${id}`);
}

export function getStructureMembers(id) {
  return request.get(`api/structure/${id}/members`);
}

export function fetchStructureMissions(id, params) {
  return request.get(`api/structure/${id}/missions`, { params });
}

export function inviteStructureMember(id, member) {
  return request.post(`api/structure/${id}/members`, member);
}

export function fetchStructures(params) {
  return request.get("/api/structures", { params });
}

export function exportStructures(params) {
  return request.get("/api/structures/export", {
    params,
    responseType: "blob"
  });
}

export function deleteStructure(id) {
  return request.delete(`api/structure/${id}`);
}

export function destroyStructure(id) {
  return request.delete(`api/structure/${id}/destroy`);
}

export function deleteMember(structureId, memberId) {
  return request.delete(`/api/structure/${structureId}/members/${memberId}`);
}
