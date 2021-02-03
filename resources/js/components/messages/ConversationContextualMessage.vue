<template>
  <div class="my-10 text-center">
    <div class="text-gray-400 font-light text-sm">
      {{ message.created_at | formatCustom('D MMM HH[h]mm') }}
    </div>
    <div class="font-semibold text-center" v-html="content"></div>
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
          message = `La participation a été validée`
          break
        case 'Annulée':
          message = `La participation a été annulée`
          break
        case 'Refusée':
          message = `La participation a été déclinée`
          break
        default:
          message = `Le nouveau statut de la participation est: ${this.message.contextual_state}`
      }

      if (
        this.message.contextual_reason &&
        this.message.contextual_reason != 'other'
      ) {
        message += `<br><span class="font-light text-sm"> ${this.$options.filters.labelFromValue(
          this.message.contextual_reason,
          'participation_declined_reasons'
        )}</span>`
      }
      return message
    },
  },
}
</script>

<style lang="sass" scoped></style>
