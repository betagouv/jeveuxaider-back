<template>
  <div>
    <el-dropdown
      v-if="canEditStatut"
      size="small"
      split-button
      :type="type"
      @click.native.stop
    >
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
          <template v-if="state.value == 'En cours de traitement'">
            En cours de traitement
          </template>
          <template v-if="state.value == 'Validée'">
            Valider la participation
          </template>
          <template v-if="state.value == 'Refusée'">
            Refuser la participation
          </template>
          <template v-if="state.value == 'Annulée'"
            >Annuler la participation</template
          >
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>

    <TagModelState
      v-else
      :state="participation.state"
      size="small"
      class="flex items-center"
    />

    <DialogParticipationDecline
      :participation="participation"
      :is-visible="declineParticipationDialog"
      @close="declineParticipationDialog = false"
      @updated="onDeclineSubmit"
      @messages-added="onMessagesAdded"
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
      if (
        ['En attente de validation', 'En cours de traitement'].includes(
          this.participation.state
        )
      ) {
        return 'warning'
      }
      return 'default'
    },
    canEditStatut() {
      if (this.$store.getters.contextRole == 'admin') {
        return true
      }
      if (
        ['referent', 'referent_regional'].includes(
          this.$store.getters.contextRole
        )
      ) {
        return false
      }
      return !![
        'En attente de validation',
        'En cours de traitement',
        'Validée',
      ].includes(this.participation.state)
    },
    statesAvailable() {
      if (this.$store.getters.contextRole == 'admin') {
        return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
          (item) => item.value != this.participation.state
        )
      }
      if (['En attente de validation'].includes(this.participation.state)) {
        return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
          (item) =>
            ['En cours de traitement', 'Validée', 'Refusée'].includes(
              item.value
            )
        )
      }
      if (['En cours de traitement'].includes(this.participation.state)) {
        return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
          (item) => ['Validée', 'Refusée'].includes(item.value)
        )
      }
      return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
        (item) =>
          !['En attente de validation', 'Validée', 'Refusée'].includes(
            item.value
          ) && item.value != this.participation.state
      )
    },
  },
  methods: {
    onDeclineSubmit(participation) {
      this.$emit('updated', participation)
    },
    onMessagesAdded($event) {
      this.$emit('messages-added', $event)
    },
    onSubmitState(state) {
      if (state == 'Refusée') {
        this.declineParticipationDialog = true
        return
      }
      if (state == 'En cours de traitement') {
        this.message = `En indiquant que la participation est en cours de traitement, vous ne recevrez plus de relances par email. Le bénévole sera notifié que vous étudiez sa demande.`
      }
      if (state == 'Validée') {
        this.message = `Vous êtes sur le point de <b>valider</b> la participation. Le bénévole sera notifié de ce changement.`
      }
      if (state == 'Annulée') {
        this.message = `Vous ou le bénéficiaire n'êtes plus en mesure d'assurer la mission, le bénévole sera averti automatiquement.`
      }

      this.$confirm(this.message, 'Changement de statut', {
        confirmButtonText: 'Je confirme',
        cancelButtonText: 'Annuler',
        dangerouslyUseHTMLString: true,
      })
        .then(() => {
          this.loading = true
          this.$api
            .updateParticipation(this.participation.id, {
              ...this.participation,
              state,
            })
            .then((response) => {
              this.$message.success({
                message: 'Le statut de la participation a été mis à jour',
              })

              this.$emit('messages-added', { count: 1 })
              this.$emit('updated', {
                ...this.participation,
                state,
              })
              this.loading = false
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
