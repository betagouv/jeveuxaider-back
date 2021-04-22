<template>
  <ConversationPage v-if="conversationIsSet" />
</template>

<script>
export default {
  layout: 'messages',
  data() {
    return {
      conversationIsSet: false,
    }
  },
  fetch() {},
  computed: {
    conversation() {
      return this.$store.getters['messaging/conversations']
        ? this.$store.getters['messaging/conversations'][0]
        : null
    },
  },
  watch: {
    conversation: {
      immediate: true,
      async handler() {
        if (this.conversation && !this.conversationIsSet) {
          this.conversationIsSet = true

          const conversation = await this.$api.getConversation(
            this.conversation.id
          )
          this.$store.commit('messaging/setConversation', conversation)

          // Remove conversation from user unread conversations
          if (
            this.$store.getters.user.unreadConversations.includes(
              conversation.id
            )
          ) {
            this.$store.commit(
              'auth/deleteConversationFromUserUnreadConversations',
              conversation.id
            )
          }
        }
      },
    },
  },
  beforeDestroy() {
    this.$store.commit('messaging/setNewMessagesCount', 0)
  },
}
</script>
