import request from "../utils/request";

export function exportYoungs(params) {
  return request.get(`/api/youngs/export`, {
    responseType: "blob",
    params
  });
}

export function getYoung(id) {
  return request.get(`api/young/${id}`);
}

export function addYoung(young) {
  return request.post(`api/young`, young);
}

export function updateYoung(id, young) {
  return request.post(`api/young/${id}`, young);
}

export function deleteYoung(id) {
  return request.delete(`api/young/${id}`);
}

export function destroyYoung(id) {
  return request.delete(`api/young/${id}/destroy`);
}

export function fetchYoungs(params) {
  return request.get("/api/youngs", { params });
}
