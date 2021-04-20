<template>
  <div>
    <el-button type="danger" @click="handleCancel"
      >Annuler ma participation</el-button
    >

    <DialogParticipationCancel
      :participation="participation"
      :is-visible="cancelParticipationDialog"
      @close="cancelParticipationDialog = false"
      @updated="onCancelSubmit"
      @messages-added="onMessagesAdded"
    />
  </div>
</template>

<script>
export default {
  name: 'ParticipationCancelButton',
  props: {
    form: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      participation: { ...this.form },
      cancelParticipationDialog: false,
    }
  },
  computed: {},
  methods: {
    onCancelSubmit(participation) {
      this.$emit('updated', participation)
    },
    onMessagesAdded($event) {
      this.$emit('messages-added', $event)
    },
    handleCancel() {
      this.cancelParticipationDialog = true
    },
  },
}
</script>
