// export const state = () => ({
//   conversations: [],
//   clickedUnreadConversationsIds: [],
//   messages: [],
//   activeConversationId: null,
// })

// export const getters = {
//   conversations: (state) => state.conversations,
//   clickedUnreadConversationsIds: (state) => state.clickedUnreadConversationsIds,
//   messages: (state) => state.messages,
//   activeConversation: (state) =>
//     state.conversations.find(
//       (conversation) => conversation.id == state.activeConversationId
//     ),
// }

// export const mutations = {
//   setConversations: (state, conversations) => {
//     state.conversations = conversations
//   },
//   setActiveConversationId: (state, conversationId) => {
//     state.activeConversationId = conversationId
//   },
//   addClickedUnreadConversationsIds: (state, conversationId) => {
//     if (!state.clickedUnreadConversationsIds.includes(conversationId)) {
//       state.clickedUnreadConversationsIds.push(conversationId)
//     }
//   },
//   updateLastestMessage: (state, message) => {
//     state.conversations.find(
//       (conversation) => conversation.id == message.conversation_id
//     ).latest_message = message
//   },
//   updateConversation: (state, conversation) => {
//     const index = state.conversations.findIndex(
//       (item) => item.id === conversation.id
//     )
//     if (index !== -1) {
//       state.conversations.splice(index, 1, conversation)
//     }
//   },
//   reset: (state) => {
//     state.conversations = []
//     state.clickedUnreadConversationsIds = []
//     state.messages = []
//     state.activeConversationId = null
//   },
// }
