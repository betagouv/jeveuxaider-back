import axios from 'axios'
// import store from '../store'

export async function refreshToken(refreshToken) {
  return await axios.post('/oauth/token', {
    grant_type: 'refresh_token',
    client_id: process.env.MIX_OAUTH_CLIENT_ID,
    client_secret: process.env.MIX_OAUTH_CLIENT_SECRET,
    refresh_token: refreshToken,
    scope: '*',
  })
}

export function logout() {
  return axios.post('/api/logout')
}

export function forgotPassword(email) {
  return axios.post('/api/password/forgot', {
    email,
  })
}

export function resetPassword(form) {
  return axios.post('/api/password/reset', form)
}

export function impersonate(id) {
  return axios.post(`/api/impersonate/${id}`)
}

export function stopImpersonate(tokenId) {
  return axios.delete(`/api/impersonate/${tokenId}`, {
    //  headers: { Authorization: `Bearer ${store.state.auth.accessToken}` },
  })
}

export function franceConnectLoginAuthorize() {
  return axios.get('/franceconnect/login-authorize')
}
export function franceConnectLoginCallback(params) {
  return axios.get('/franceconnect/login-callback', { params })
}