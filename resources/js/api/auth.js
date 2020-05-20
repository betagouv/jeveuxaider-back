import request from '../utils/request'
import store from '../store'

export function login(email, password) {
  return request.post('/oauth/token', {
    grant_type: 'password',
    client_id: process.env.MIX_OAUTH_CLIENT_ID,
    client_secret: process.env.MIX_OAUTH_CLIENT_SECRET,
    username: email,
    password: password,
    scope: '*',
  })
}

export async function refreshToken(refreshToken) {
  return await request.post('/oauth/token', {
    grant_type: 'refresh_token',
    client_id: process.env.MIX_OAUTH_CLIENT_ID,
    client_secret: process.env.MIX_OAUTH_CLIENT_SECRET,
    refresh_token: refreshToken,
    scope: '*',
  })
}

export function logout() {
  return request.post('/api/logout')
}

export function forgotPassword(email) {
  return request.post('/api/password/forgot', {
    email,
  })
}

export function resetPassword(form) {
  return request.post('/api/password/reset', form)
}

export function impersonate(id) {
  return request.post(`/api/impersonate/${id}`)
}

export function stopImpersonate(token_id) {
  return request.delete(`/api/impersonate/${token_id}`, {
    headers: { Authorization: `Bearer ${store.state.auth.accessToken}` },
  })
}
