<template>
  <ConversationNewPage />
</template>

<script>
export default {
  layout: 'messages',
  fetch() {
    if (this.$store.getters['messaging/conversations'].length > 0) {
      const conversation = this.$store.getters['messaging/conversations'][0]
      this.$store.commit('messaging/setConversation', conversation)

      // Remove conversation from user unread conversations
      if (
        this.$store.getters.user.unreadConversations.includes(conversation.id)
      ) {
        this.$store.commit(
          'auth/deleteConversationFromUserUnreadConversations',
          conversation.id
        )
      }
    }
  },
  beforeDestroy() {
    this.$store.commit('messaging/setNewMessagesCount', 0)
  },
}
</script>
