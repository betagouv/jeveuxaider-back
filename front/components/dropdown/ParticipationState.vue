<template>
  <div>
    <el-dropdown v-if="canEditStatut" size="small" split-button :type="type">
      <div style="min-width: 140px; text-align: left">
        <template v-if="loading"> Chargement... </template>
        <template v-else>{{ participation.state }}</template>
      </div>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item
          v-for="state in statesAvailable"
          :key="state.value"
          @click.native="onSubmitState(state.value)"
        >
          <template v-if="state.value == 'En attente de validation'"
            >Repasser en validation</template
          >
          <template v-if="state.value == 'Validée'"
            >Valider la participation</template
          >
          <template v-if="state.value == 'Refusée'"
            >Refuser la participation</template
          >
          <template v-if="state.value == 'Annulée'"
            >Annuler la participation</template
          >
          <template v-if="state.value == 'Effectuée'"
            >Terminer la mission</template
          >
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
    <template v-else>
      <div class="text-sm">{{ participation.state }}</div>
    </template>

    <DialogParticipationDecline
      :participation="participation"
      :is-visible="declineParticipationDialog"
      @close="declineParticipationDialog = false"
      @updated="onDeclineSubmit"
    />
  </div>
</template>

<script>
export default {
  name: 'ParticipationDropdownState',
  props: {
    participation: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.participation },
      declineParticipationDialog: false,
      messageForm: {},
      message: 'Êtes vous sur de vos changements ?',
      rules: {
        reason: [
          {
            required: true,
            message: 'Merci de sélectionner une raison',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  computed: {
    type() {
      if (this.participation.state == 'En attente de validation') {
        return 'warning'
      }
      return 'default'
    },
    canEditStatut() {
      if (this.$store.getters.contextRole == 'admin') {
        return true
      }
      return !!['En attente de validation', 'Validée'].includes(
        this.participation.state
      )
    },
    statesAvailable() {
      if (this.$store.getters.contextRole == 'admin') {
        return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
          (item) => item.value != this.participation.state
        )
      } else if (this.participation.state == 'En attente de validation') {
        return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
          (item) => ['Validée', 'Refusée'].includes(item.value)
        )
      } else {
        return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
          (item) =>
            !['En attente de validation', 'Validée', 'Refusée'].includes(
              item.value
            ) && item.value != this.participation.state
        )
      }
    },
  },
  methods: {
    onDeclineSubmit() {
      this.form.state = 'Refusée'
      this.$emit('updated')
    },
    onSubmitState(state) {
      if (state == 'Refusée') {
        this.declineParticipationDialog = true
        return
      }
      if (state == 'Validée') {
        this.message = `Vous êtes sur le point de <b>valider</b> la participation. Le bénévole sera notifié de ce changement.`
      }
      if (state == 'Effectuée') {
        this.message = `Le réserviste a terminé la mission. Il sera notifié de ce changement.`
      }
      if (state == 'Annulée') {
        this.message = `Vous ou le bénéficiaire n'êtes plus en mesure d'assurer la mission, le réserviste sera averti automatiquement.`
      }

      this.$confirm(this.message, 'Changement de statut', {
        confirmButtonText: 'Je confirme',
        cancelButtonText: 'Annuler',
        dangerouslyUseHTMLString: true,
      })
        .then(() => {
          this.loading = true
          this.form.state = state
          this.$api
            .updateParticipation(this.form.id, this.form)
            .then((response) => {
              this.$message.success({
                message: 'Le statut de la participation a été mis à jour',
              })
              this.loading = false
              this.$emit('updated', response.data)
            })
            .catch((error) => {
              this.loading = false
              this.errors = error.response.data.errors
            })
        })
        .catch(() => {})
    },
  },
}
</script>
