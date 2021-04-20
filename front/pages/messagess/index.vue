<template>
  <ConversationNewPage />
</template>

<script>
export default {
  layout: 'messages',
  fetch() {
    this.$store.commit(
      'messaging/setConversation',
      this.$store.getters['messaging/conversations'][0]
    )
  },
}
</script>
