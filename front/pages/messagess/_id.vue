<template>
  <ConversationNewPage />
</template>

<script>
export default {
  layout: 'messages',
  async asyncData({ store, error, $api, params }) {
    const conversation = await $api.getConversation2(params.id)

    if (!conversation) {
      return error({ statusCode: 403 })
    }

    store.commit('messaging/setConversation', conversation)
  },
  mounted() {
    console.log('_id MOUNTED')
    if (this.$store.getters['messaging/isMobile']) {
      this.$store.commit('messaging/setShowPanelLeft', false)
    }
    this.$store.commit('messaging/setShowPanelCenter', true)
  },
}
</script>
