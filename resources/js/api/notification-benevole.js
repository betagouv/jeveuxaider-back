import request from '../utils/request'

export function fetchNofiticationsBenevoles(params) {
  return request.get('/api/notifications-benevoles', { params })
}

export function addNotificationBenevole(mission_id, profile_id) {
  return request.post('/api/notification-benevole', {
    mission_id,
    profile_id,
  })
}
