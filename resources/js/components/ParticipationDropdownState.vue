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
          <template v-if="state.value == 'Mission validée'"
            >Valider la participation</template
          >
          <template
            v-if="
              form.state == 'En attente de validation' &&
              state.value == 'Participation déclinée'
            "
            >Décliner la participation</template
          >
          <template v-if="state.value == 'Mission signalée'"
            >Signaler la participation</template
          >
          <template
            v-if="
              form.state == 'Mission validée' &&
              state.value == 'Mission annulée'
            "
            >Annuler la participation</template
          >
          <template
            v-if="
              form.state == 'Mission validée' &&
              state.value == 'Mission effectuée'
            "
            >Terminer la mission</template
          >
          <template
            v-if="
              form.state == 'Mission validée' &&
              state.value == 'Mission abandonnée'
            "
            >Le réserviste a abandonné</template
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
      return ['En attente de validation', 'Mission validée'].includes(
        this.form.state
      )
        ? true
        : false
    },
    statesAvailable() {
      return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
        (item) =>
          !['En attente de validation', 'Mission signalée'].includes(
            item.value
          ) && item.value != this.form.state
      )
    },
  },
  methods: {
    onSubmitState(state) {
      if (state == 'Mission validée') {
        this.message = `Vous êtes sur le point de <b>valider</b> la participation. Le volontaire sera notifié de ce changement.`
      }
      if (state == 'Mission effectuée') {
        this.message = `Le réserviste a terminé la mission. Il sera notifié de ce changement.`
      }
      if (state == 'Mission abandonnée') {
        this.message = `Vous êtes sans nouvelles après avoir accepté sa participation et lui avoir indiqué où quand et comment commencer sa mission. Le volontaire sera notifié de ce changement.`
      }
      if (state == 'Participation déclinée') {
        this.message = `Vous ne retenez pas cette candidature, vous libérez la place pour un autre candidat. Le volontaire sera notifié de ce changement.`
      }
      if (state == 'Mission annulée') {
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
