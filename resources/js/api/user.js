import request from "../utils/request";

export function registerVolontaire(email, password, first_name, last_name, mobile, birthday, zip) {
  return request.post("/api/register/volontaire", {
    email,
    password,
    first_name,
    last_name,
    mobile,
    birthday,
    zip
  });
}

export function registerResponsable(email, password, first_name, last_name) {
  return request.post("/api/register/responsable", {
    email,
    password,
    first_name,
    last_name
  });
}

export function exportProfiles(params) {
  return request.get(`/api/profiles/export`, {
    responseType: "blob",
    params
  });
}

export async function getUser() {
  return await request.get("/api/user");
}

export function getProfile(id) {
  return request.get(`api/profile/${id}`);
}

export function addProfile(profile) {
  return request.post(`api/profile`, profile);
}

export function updateProfile(id, profile) {
  return request.post(`api/profile/${id}`, profile);
}

export function updateProfileAvatar(id, avatar) {
  var data = new FormData();
  data.append("avatar", avatar);
  return request.post(`api/profile/${id}`, data, {
    header: { "Content-Type": "multipart/form-data" }
  });
}

export function updateUser(user) {
  return request.post("/api/user", user);
}

export function updatePassword(user) {
  return request.post("/api/user/password", user);
}

export function fetchUsers() {
  return request.get("/api/users");
}

export function fetchProfiles(params) {
  return request.get("/api/profiles", { params });
}

export const rolesList = [
  { key: "admin", label: "Modérateur" },
  { key: "referent", label: "Référent" },
  { key: "superviseur", label: "Superviseur" },
  { key: "responsable", label: "Responsable" },
  { key: "tuteur", label: "Tuteur" }
];
