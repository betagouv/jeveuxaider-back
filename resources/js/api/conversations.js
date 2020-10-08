import request from '../utils/request'

export function fetchConversations(params) {
  return request.get('/api/conversations', { params })
}

export function fetchMessages(conversation_id) {
  return request.get(`/api/conversations/${conversation_id}/messages`)
}

export function addMessage(message) {
  return request.post('/api/messages', message)
}
