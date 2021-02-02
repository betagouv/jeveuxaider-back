<template>
  <div class="my-10 text-center">
    <div class="text-gray-400 font-light text-sm">
      {{ message.created_at | formatCustom('D MMM HH[h]mm') }}
    </div>
    <div class="font-semibold text-center">
      {{ content }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'ConversationContextualMessage',
  props: {
    message: {
      type: Object,
      required: true,
    },
  },
  computed: {
    content() {
      let message = ''
      switch (this.message.contextual_state) {
        case 'Validée':
          message = `Votre participation a été validée`
          break
        case 'Annulée':
          message = `Votre participation a été annulée`
          break
        case 'Refusée':
          message = `Votre participation a été déclinée`
          break
        default:
          message = `Le nouveau statut de votre participation est: ${this.message.contextual_state}`
      }
      return message
    },
  },
}
</script>

<style lang="sass" scoped></style>
