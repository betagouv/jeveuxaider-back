import axios from "axios"

export function fetchActivity(id) {
  return axios.get(`/activity/${id}`);
}

export function fetchActivities(params) {
  return axios.get("/activities", { params });
}

export function addActivity(activity) {
  return axios.post("/activity", activity);
}

export function updateActivity(id, activity) {
  return axios.post(`/activity/${id}`, activity);
}

export function addOrUpdateActivity(id, activity) {
  return id ? updateActivity(id, activity) : addActivity(activity);
}

export function fetchBudget(id, bid) {
  return axios.get(`/activity/${id}/budget/${bid}`);
}

export function addBudget(id, budget) {
  return axios.post(`/activity/${id}/budget`, budget);
}

export function updateBudget(id, bid, budget) {
  return axios.post(`/activity/${id}/budget/${bid}`, budget);
}

export function deleteBudget(id, bid) {
  return axios.delete(`/activity/${id}/budget/${bid}`);
}

export function addOrUpdateBudget(id, bid, budget) {
  return bid ? updateBudget(id, bid, budget) : addBudget(id, budget);
}



