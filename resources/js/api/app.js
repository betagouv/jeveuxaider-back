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
