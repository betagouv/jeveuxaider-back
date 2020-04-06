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
