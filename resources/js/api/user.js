import axios from "axios"

export function fetchProfiles(params) {
  return axios.get("/profiles", { params });
}
