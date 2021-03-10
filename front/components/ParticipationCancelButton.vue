<template>
  <div>
    <el-button type="danger" @click="handleCancel"
      >Annuler ma participation</el-button
    >

    <participation-cancel
      :participation="participation"
      :is-visible="cancelParticipationDialog"
      @close="cancelParticipationDialog = false"
      @updated="onCancelSubmit"
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
    onCancelSubmit() {
      this.participation.state = 'Annulée'
      this.$emit('updated')
    },
    handleCancel() {
      // this.form.state = 'Annulée'
      this.cancelParticipationDialog = true
    },
  },
}
</script>
