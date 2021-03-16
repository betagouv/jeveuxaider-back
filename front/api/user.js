export const rolesList = [
  { key: 'admin', label: 'Modérateur' },
  { key: 'referent', label: 'Référent' },
  { key: 'referent_regional', label: 'Régional' },
  { key: 'superviseur', label: 'Superviseur' },
  { key: 'responsable', label: 'Responsable' },
  { key: 'volontaire', label: 'Bénévole' },
  { key: 'analyste', label: 'Analyste' },
]

export default (axios) => ({
  async fetchProfileParticipations(id) {
    const { data } = await axios.get(`/profile/${id}/participations`)
    return data
  },
  async updateProfile(id, profile) {
    const { data } = await axios.post(`/profile/${id}`, profile)
    return data
  },

  async updatePassword(user) {
    return await axios.post('/user/password', user)
  },

  async forgotPassword(email) {
    return await axios.post('/password/forgot', {
      email,
    })
  },

  async resetPassword(form) {
    return await axios.post('/password/reset', form)
  },

  async anonymizeUser() {
    return await axios.post('/user/anonymize')
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
    const { data } = await axios.get(`/invitation/${token}`)
    return data
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
  async fetchProfiles(params, appends) {
    return await axios.get(`/profiles?append=${appends.join(',')}`, {
      params,
    })
  },
  async exportProfilesReferentsDepartements(params) {
    return await axios.get(`/profiles/referents/departements/export`, {
      responseType: 'blob',
      params,
    })
  },
  async exportProfilesReferentsRegions(params) {
    return await axios.get(`/profiles/referents/regions/export`, {
      responseType: 'blob',
      params,
    })
  },
  async exportProfilesResponsables(params) {
    return await axios.get(`/profiles/responsables/export`, {
      responseType: 'blob',
      params,
    })
  },
  async getProfile(id) {
    const { data } = await axios.get(`/profile/${id}`)
    return data
  },

  async fetchNofiticationsBenevoles(params) {
    return await axios.get('/notifications-benevoles', { params })
  },

  async addNotificationBenevole(missionId, profileId) {
    return await axios.post('/notification-benevole', {
      mission_id: missionId,
      profile_id: profileId,
    })
  },
  registerVolontaire(
    email,
    password,
    firstName,
    lastName,
    mobile,
    birthday,
    zip,
    sc
  ) {
    return axios.post('/register/volontaire', {
      email: email.toLowerCase(),
      password,
      first_name: firstName,
      last_name: lastName,
      mobile,
      birthday,
      zip,
      sc,
    })
  },

  async registerResponsable(
    email,
    password,
    firstName,
    lastName,
    structureName
  ) {
    return await axios.post('/register/responsable', {
      email: email.toLowerCase(),
      password,
      first_name: firstName,
      last_name: lastName,
      structure_name: structureName,
    })
  },

  async getUserFirstname(email) {
    return await axios.post(`/firstname`, {
      email,
    })
  },
})

// import axios from 'axios'

// async exportProfiles(params) {
//   return axios.get(`/profiles/export`, {
//     responseType: 'blob',
//     params,
//   })
// }

// export async function getUser() {
//   return await axios.get('/user')
// }

// async addProfile(profile) {
//   return axios.post(`/profile`, profile)
// }

// async updateUser(user) {
//   return axios.post('/user', user)
// }

// async fetchUsers(params, appends) {
//   return axios.get(`/users?append=${appends.join(',')}`, {
//     params,
//   })
// }
