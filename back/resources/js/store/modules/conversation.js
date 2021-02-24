const state = {
  conversations: [],
  messages: [],
  activeConversationId: null,
}

const getters = {
  conversations: (state) => state.conversations,
  messages: (state) => state.messages,
  activeConversation: (state) =>
    state.conversations.find(
      (conversation) => conversation.id == state.activeConversationId
    ),
}

// mutations
const mutations = {
  setConversations: (state, conversations) => {
    state.conversations = conversations
  },
  setActiveConversationId: (state, conversationId) => {
    state.activeConversationId = conversationId
  },
}

export default {
  namespaced: true,
  getters,
  state,
  mutations,
}
