<template>
  <ConversationPage />
</template>

<script>
export default {
  layout: 'messages',
  async asyncData({ store, error, $api, params }) {
    const conversation = await $api.getConversation(params.id)

    if (!conversation) {
      return error({ statusCode: 403 })
    }

    store.commit('messaging/setConversation', conversation)

    // Remove conversation from user unread conversations
    if (store.getters.user.unreadConversations.includes(conversation.id)) {
      store.commit(
        'auth/deleteConversationFromUserUnreadConversations',
        conversation.id
      )
    }
  },
  mounted() {
    if (this.$store.getters['messaging/isMobile']) {
      this.$store.commit('messaging/setShowPanelLeft', false)
    }
    this.$store.commit('messaging/setShowPanelCenter', true)
  },
  beforeDestroy() {
    this.$store.commit('messaging/setNewMessagesCount', 0)
  },
}
</script>
