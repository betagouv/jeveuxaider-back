export default (axios) => ({
  async fetchMessages(conversationId, params) {
    return await axios.get(`/conversations/${conversationId}/messages`, {
      params,
    })
  },
  async addMessageToConversation(conversationId, params) {
    return await axios.post(`/conversations/${conversationId}/messages`, params)
  },
  async fetchConversations2(params) {
    return await axios.get('/conversations2', { params })
  },
  async getConversation2(id) {
    const { data } = await axios.get(`/conversation2/${id}`)
    return data
  },
  async getConversationBenevole(id) {
    const { data } = await axios.get(`/conversation2/${id}/benevole`)
    return data
  },
})
