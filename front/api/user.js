import axios from 'axios'

export function registerVolontaire(
  email,
  password,
  firstName,
  lastName,
  mobile,
  birthday,
  zip,
  sc
) {
  return axios.post('/api/register/volontaire', {
    email: email.toLowerCase(),
    password,
    firstName,
    lastName,
    mobile,
    birthday,
    zip,
    sc,
  })
}

export function registerResponsable(
  email,
  password,
  firstName,
  lastName,
  structureName
) {
  return axios.post('/api/register/responsable', {
    email: email.toLowerCase(),
    password,
    firstName,
    lastName,
    structureName,
  })
}

export function exportProfiles(params) {
  return axios.get(`/api/profiles/export`, {
    responseType: 'blob',
    params,
  })
}

export function exportProfilesReferentsDepartements(params) {
  return axios.get(`/api/profiles/referents/departements/export`, {
    responseType: 'blob',
    params,
  })
}

export function exportProfilesReferentsRegions(params) {
  return axios.get(`/api/profiles/referents/regions/export`, {
    responseType: 'blob',
    params,
  })
}

export function exportProfilesResponsables(params) {
  return axios.get(`/api/profiles/responsables/export`, {
    responseType: 'blob',
    params,
  })
}

export async function getUser() {
  return await axios.get('/api/user')
}

export function getProfile(id) {
  return axios.get(`/api/profile/${id}`)
}

export function addProfile(profile) {
  return axios.post(`/api/profile`, profile)
}

export function updateProfile(id, profile) {
  return axios.post(`/api/profile/${id}`, profile)
}

export function updateUser(user) {
  return axios.post('/api/user', user)
}

export function updatePassword(user) {
  return axios.post('/api/user/password', user)
}

export function anonymizeUser() {
  return axios.post('/api/user/anonymize')
}

export function fetchProfileParticipations(id) {
  return axios.get(`/api/profile/${id}/participations`)
}

export function fetchProfiles(params, appends) {
  return axios.get(`/api/profiles?append=${appends.join(',')}`, {
    params,
  })
}

export function fetchUsers(params, appends) {
  return axios.get(`/api/users?append=${appends.join(',')}`, {
    params,
  })
}

export function fetchInvitations(params) {
  return axios.get(`/api/invitations`, {
    params,
  })
}

export function addInvitation(invitation) {
  return axios.post(`/api/invitation`, invitation)
}

export function getInvitation(token) {
  return axios.get(`/api/invitation/${token}`)
}

export function acceptInvitation(token) {
  return axios.post(`/api/invitation/${token}/accept`)
}

export function deleteInvitation(token) {
  return axios.delete(`/api/invitation/${token}/delete`)
}

export function resendInvitation(token) {
  return axios.post(`/api/invitation/${token}/resend`)
}

export function registerInvitation(params, token) {
  return axios.post(`/api/invitation/${token}/register`, params)
}

export const rolesList = [
  { key: 'admin', label: 'Modérateur' },
  { key: 'referent', label: 'Référent' },
  { key: 'referent_regional', label: 'Régional' },
  { key: 'superviseur', label: 'Superviseur' },
  { key: 'responsable', label: 'Responsable' },
  { key: 'volontaire', label: 'Bénévole' },
  { key: 'analyste', label: 'Analyste' },
]

export function getUserFirstname(email) {
  return axios.post(`/api/firstname`, {
    email,
  })
}
