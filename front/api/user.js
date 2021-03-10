export default (axios) => ({
  async fetchProfileParticipations(id) {
    const { data } = await axios.get(`/profile/${id}/participations`)
    return data
  },
  async updateProfile(id, profile) {
    const { data } = await axios.post(`/profile/${id}`, profile)
    return data
  },
  async fetchInvitations(params) {
    return await axios.get(`/invitations`, {
      params,
    })
  },
  async addInvitation(invitation) {
    return await axios.post(`/invitation`, invitation)
  },
  async getInvitation(token) {
    return await axios.get(`/invitation/${token}`)
  },
  async acceptInvitation(token) {
    return await axios.post(`/invitation/${token}/accept`)
  },
  async deleteInvitation(token) {
    return await axios.delete(`/invitation/${token}/delete`)
  },
  async resendInvitation(token) {
    return await axios.post(`/invitation/${token}/resend`)
  },
  async registerInvitation(params, token) {
    return await axios.post(`/invitation/${token}/register`, params)
  },
})

// import axios from 'axios'

// async registerVolontaire(
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

// async registerResponsable(
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

// async exportProfiles(params) {
//   return axios.get(`/profiles/export`, {
//     responseType: 'blob',
//     params,
//   })
// }

// async exportProfilesReferentsDepartements(params) {
//   return axios.get(`/profiles/referents/departements/export`, {
//     responseType: 'blob',
//     params,
//   })
// }

// async exportProfilesReferentsRegions(params) {
//   return axios.get(`/profiles/referents/regions/export`, {
//     responseType: 'blob',
//     params,
//   })
// }

// async exportProfilesResponsables(params) {
//   return axios.get(`/profiles/responsables/export`, {
//     responseType: 'blob',
//     params,
//   })
// }

// export async function getUser() {
//   return await axios.get('/user')
// }

// async getProfile(id) {
//   return axios.get(`/profile/${id}`)
// }

// async addProfile(profile) {
//   return axios.post(`/profile`, profile)
// }

// async updateUser(user) {
//   return axios.post('/user', user)
// }

// async updatePassword(user) {
//   return axios.post('/user/password', user)
// }

// async anonymizeUser() {
//   return axios.post('/user/anonymize')
// }

// async fetchProfiles(params, appends) {
//   return axios.get(`/profiles?append=${appends.join(',')}`, {
//     params,
//   })
// }

// async fetchUsers(params, appends) {
//   return axios.get(`/users?append=${appends.join(',')}`, {
//     params,
//   })
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

// async getUserFirstname(email) {
//   return axios.post(`/firstname`, {
//     email,
//   })
// }
