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

export function exportTable(table) {
  return request.post(`/api/${table}/export/table`);
}
