export default (axios) => ({
  async fetchProfileParticipations(id) {
    const { data } = await axios.get(`/profile/${id}/participations`)
    return data
  },
})

// import axios from 'axios'

// export function registerVolontaire(
//   email,
//   password,
//   firstName,
//   lastName,
//   mobile,
//   birthday,
//   zip,
//   sc
// ) {
//   return axios.post('/register/volontaire', {
//     email: email.toLowerCase(),
//     password,
//     firstName,
//     lastName,
//     mobile,
//     birthday,
//     zip,
//     sc,
//   })
// }

// export function registerResponsable(
//   email,
//   password,
//   firstName,
//   lastName,
//   structureName
// ) {
//   return axios.post('/register/responsable', {
//     email: email.toLowerCase(),
//     password,
//     firstName,
//     lastName,
//     structureName,
//   })
// }

// export function exportProfiles(params) {
//   return axios.get(`/profiles/export`, {
//     responseType: 'blob',
//     params,
//   })
// }

// export function exportProfilesReferentsDepartements(params) {
//   return axios.get(`/profiles/referents/departements/export`, {
//     responseType: 'blob',
//     params,
//   })
// }

// export function exportProfilesReferentsRegions(params) {
//   return axios.get(`/profiles/referents/regions/export`, {
//     responseType: 'blob',
//     params,
//   })
// }

// export function exportProfilesResponsables(params) {
//   return axios.get(`/profiles/responsables/export`, {
//     responseType: 'blob',
//     params,
//   })
// }

// export async function getUser() {
//   return await axios.get('/user')
// }

// export function getProfile(id) {
//   return axios.get(`/profile/${id}`)
// }

// export function addProfile(profile) {
//   return axios.post(`/profile`, profile)
// }

// export function updateProfile(id, profile) {
//   return axios.post(`/profile/${id}`, profile)
// }

// export function updateUser(user) {
//   return axios.post('/user', user)
// }

// export function updatePassword(user) {
//   return axios.post('/user/password', user)
// }

// export function anonymizeUser() {
//   return axios.post('/user/anonymize')
// }

// export function fetchProfiles(params, appends) {
//   return axios.get(`/profiles?append=${appends.join(',')}`, {
//     params,
//   })
// }

// export function fetchUsers(params, appends) {
//   return axios.get(`/users?append=${appends.join(',')}`, {
//     params,
//   })
// }

// export function fetchInvitations(params) {
//   return axios.get(`/invitations`, {
//     params,
//   })
// }

// export function addInvitation(invitation) {
//   return axios.post(`/invitation`, invitation)
// }

// export function getInvitation(token) {
//   return axios.get(`/invitation/${token}`)
// }

// export function acceptInvitation(token) {
//   return axios.post(`/invitation/${token}/accept`)
// }

// export function deleteInvitation(token) {
//   return axios.delete(`/invitation/${token}/delete`)
// }

// export function resendInvitation(token) {
//   return axios.post(`/invitation/${token}/resend`)
// }

// export function registerInvitation(params, token) {
//   return axios.post(`/invitation/${token}/register`, params)
// }

export const rolesList = [
  { key: 'admin', label: 'Modérateur' },
  { key: 'referent', label: 'Référent' },
  { key: 'referent_regional', label: 'Régional' },
  { key: 'superviseur', label: 'Superviseur' },
  { key: 'responsable', label: 'Responsable' },
  { key: 'volontaire', label: 'Bénévole' },
  { key: 'analyste', label: 'Analyste' },
]

// export function getUserFirstname(email) {
//   return axios.post(`/firstname`, {
//     email,
//   })
// }
