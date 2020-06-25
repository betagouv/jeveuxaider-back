<template>
  <div>
    <el-dropdown v-if="canEditStatut" size="small" split-button :type="type">
      <div style="min-width: 140px; text-align: left;">{{ form.state }}</div>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item
          v-for="state in statesAvailable"
          :key="state.value"
          @click.native="onSubmitState(state.value)"
        >
          <template v-if="state.value == 'Validée'"
            >Valider la participation</template
          >
          <template
            v-if="
              form.state == 'En attente de validation' &&
              state.value == 'Refusée'
            "
            >Refuser la participation</template
          >
          <template v-if="form.state == 'Validée' && state.value == 'Annulée'"
            >Annuler la participation</template
          >
          <template v-if="form.state == 'Validée' && state.value == 'Effectuée'"
            >Terminer la mission</template
          >
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
    <template v-else>
      <div class="text-sm">{{ form.state }}</div>
    </template>
  </div>
</template>

<script>
import { updateParticipation } from '@/api/participation'

export default {
  name: 'ParticipationDropdownState',
  props: {
    form: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      message: 'Êtes vous sur de vos changements ?',
    }
  },
  computed: {
    type() {
      if (this.form.state == 'En attente de validation') {
        return 'warning'
      }
      return 'default'
    },
    canEditStatut() {
      return ['En attente de validation', 'Validée'].includes(this.form.state)
        ? true
        : false
    },
    statesAvailable() {
      return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
        (item) =>
          !['En attente de validation'].includes(item.value) &&
          item.value != this.form.state
      )
    },
  },
  methods: {
    onSubmitState(state) {
      if (state == 'Validée') {
        this.message = `Vous êtes sur le point de <b>valider</b> la participation. Le volontaire sera notifié de ce changement.`
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
          this.form.state = state
          updateParticipation(this.form.id, this.form)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Le statut de la participation a été mis à jour',
              })
              this.$emit('updated', response.data)
            })
            .catch((error) => {
              this.errors = error.response.data.errors
            })
        })
        .catch(() => {})
    },
  },
}
</script>
