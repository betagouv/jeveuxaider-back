export default (axios) => ({
  async fetchMessages(conversationId, params) {
    return await axios.get(`/conversations/${conversationId}/messages`, {
      params,
    })
  },
  async addMessageToConversation(conversationId, params) {
    return await axios.post(`/conversations/${conversationId}/messages`, params)
  },
  async fetchConversations(params) {
    return await axios.get('/conversations', { params })
  },
  async getConversation(id) {
    const { data } = await axios.get(`/conversation/${id}`)
    return data
  },
  async getConversationBenevole(id) {
    const { data } = await axios.get(`/conversation/${id}/benevole`)
    return data
  },
  async setConversationStatus(id, status) {
    return await axios.post(`/conversation/${id}/setStatus`, status)
  },
})
