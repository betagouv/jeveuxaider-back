export default (axios) => ({
  async fetchConversations(params) {
    return await axios.get('/conversations', { params })
  },
  async fetchMessages(conversationId, params) {
    return await axios.get(`/conversations/${conversationId}/messages`, {
      params,
    })
  },
  async addMessageToConversation(conversationId, params) {
    return await axios.post(`/conversations/${conversationId}/messages`, params)
  },
})
