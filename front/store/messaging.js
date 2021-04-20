/* eslint-disable prettier/prettier */

export const state = () => ({
  conversations: [],
  conversation: null,
  messages: [],
  newMessagesCount: 0,
  showPanelLeft: true,
  showPanelCenter: false,
  showPanelRight: false,
  isMobile: true,
  isDesktop: false,
})

export const getters = {
  conversations: (state) => state.conversations,
  conversation: (state) => state.conversation,
  messages: (state) => state.messages,
  newMessagesCount: (state) => state.newMessagesCount,
  isMobile: (state) => state.isMobile,
  isDesktop: (state) => state.isDesktop,
  showPanelLeft: (state) => state.showPanelLeft,
  showPanelCenter: (state) => state.showPanelCenter,
  showPanelRight: (state) => state.showPanelRight,
}

export const mutations = {
  setConversations: (state, conversations) => {
    console.log('STORE setConversations')

    state.conversations = conversations
  },
  setConversation: (state, payload) => {
    console.log('STORE setConversation', payload)

    state.conversations.splice(
      state.conversations.findIndex(
        (conversation) => conversation.id == payload.id
      ),
      1,
      payload
    )

    state.conversation = payload
  },
  setMessages: (state, messages) => {
    console.log('STORE setMessages')
    state.messages = messages
  },
  incrementNewMessagesCount: (state, count = 1) => {
    console.log('STORE incrementNewMessagesCount', count)
    state.newMessagesCount = state.newMessagesCount + count
  },
  setIsMobile: (state, isMobile) => {
    state.isMobile = isMobile
  },
  setIsDesktop: (state, isDesktop) => {
    state.isDesktop = isDesktop
  },
  setShowPanelLeft: (state, showPanelLeft) => {
    state.showPanelLeft = showPanelLeft
  },
  setShowPanelCenter: (state, showPanelCenter) => {
    state.showPanelCenter = showPanelCenter
  },
  setShowPanelRight: (state, showPanelRight) => {
    state.showPanelRight = showPanelRight
  },
}

export const actions = {
  async refreshConversation({ commit }, conversationId) {
    console.log('STORE refreshConversation')

    const conversation = await this.$api.getConversation2(conversationId)
    commit('setConversation', conversation)
  },
}
