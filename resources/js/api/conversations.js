import request from '../utils/request'

export function fetchConversations(params) {
  return request.get('/api/conversations', { params })
}

export function fetchMessages(conversation_id, params) {
  return request.get(`/api/conversations/${conversation_id}/messages`, {
    params,
  })
}

export function addMessage(message) {
  return request.post('/api/messages', message)
}
