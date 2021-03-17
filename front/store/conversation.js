export const state = () => ({
  conversations: [],
  messages: [],
  activeConversationId: null,
})

export const getters = {
  conversations: (state) => state.conversations,
  messages: (state) => state.messages,
  activeConversation: (state) =>
    state.conversations.find(
      (conversation) => conversation.id == state.activeConversationId
    ),
}

export const mutations = {
  setConversations: (state, conversations) => {
    state.conversations = conversations
  },
  setActiveConversationId: (state, conversationId) => {
    state.activeConversationId = conversationId
  },
  updateLastestMessage: (state, message) => {
    state.conversations.find(
      (conversation) => conversation.id == message.conversation_id
    ).latest_message = message
  },
}
