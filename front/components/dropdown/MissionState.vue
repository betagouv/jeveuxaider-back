<template>
  <div>
    <template v-if="showAskValidation">
      <el-dropdown size="small" split-button :type="type">
        <div style="min-width: 140px; text-align: left">
          <template v-if="loading"> Chargement... </template>
          <template v-else> {{ mission.state }} </template>
        </div>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item @click.native="onAskValidationSubmit">
            Publier la mission
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </template>
    <template v-else>
      <el-dropdown v-if="canEditStatut" size="small" split-button :type="type">
        <div style="min-width: 140px; text-align: left">
          <template v-if="loading"> Chargement... </template>
          <template v-else> {{ mission.state }} </template>
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
              >Valider la mission</template
            >
            <template
              v-if="
                (mission.state == 'Validée' ||
                  $store.getters.contextRole == 'admin') &&
                state.value == 'Terminée'
              "
              >Terminer la mission</template
            >
            <template
              v-if="
                (mission.state == 'Validée' ||
                  $store.getters.contextRole == 'admin') &&
                state.value == 'Annulée'
              "
              >Annuler la mission</template
            >
            <template v-if="state.value == 'Signalée'"
              >Signaler la mission</template
            >
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <template v-else>
        <div class="text-sm leading-normal">{{ mission.state }}</div>
      </template>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    mission: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.mission },
      message: 'Êtes vous sur de vos changements ?',
    }
  },
  computed: {
    type() {
      if (this.mission.state == 'En attente de validation') {
        return 'warning'
      }
      return 'default'
    },
    showAskValidation() {
      return !!(
        this.$store.getters.contextRole == 'responsable' &&
        this.mission.state == 'Brouillon'
      )
    },
    canEditStatut() {
      if (this.$store.getters.contextRole == 'admin') {
        return true
      }
      if (this.mission.state == 'Validée') {
        return true
      }
      if (
        this.$store.getters.contextRole == 'referent' ||
        this.$store.getters.contextRole == 'referent_regional'
      ) {
        return !['Signalée'].includes(this.mission.state)
      }
      return false
    },
    statesAvailable() {
      if (this.$store.getters.contextRole == 'admin') {
        return this.$store.getters.taxonomies.mission_workflow_states.terms.filter(
          (item) => item.value != this.mission.state
        )
      } else if (this.$store.getters.contextRole == 'responsable') {
        return this.$store.getters.taxonomies.mission_workflow_states.terms.filter(
          (item) =>
            !['Signalée', 'En attente de validation'].includes(item.value) &&
            item.value != this.mission.state
        )
      } else if (
        this.$store.getters.contextRole == 'referent' ||
        this.$store.getters.contextRole == 'referent_regional'
      ) {
        return this.$store.getters.taxonomies.mission_workflow_states.terms.filter(
          (item) =>
            ['Signalée', 'Validée'].includes(item.value) &&
            item.value != this.mission.state
        )
      } else {
        return this.$store.getters.taxonomies.mission_workflow_states.terms.filter(
          (item) =>
            !['En attente de validation'].includes(item.value) &&
            item.value != this.mission.state
        )
      }
    },
  },
  watch: {
    mission(newValue) {
      this.form = { ...newValue }
    },
  },
  methods: {
    onAskValidationSubmit() {
      if (this.mission.structure.state != 'Validée') {
        this.$message({
          type: 'error',
          message:
            'Votre organisation doit être validée avant de pouvoir publier une mission',
        })
      } else if (this.mission.template_id) {
        this.onSubmitState('Validée')
      } else {
        this.onSubmitState('En attente de validation')
      }
    },
    onSubmitState(state) {
      if (state == 'En attente de validation') {
        this.message = `Une demande de publication va être envoyée aux référents de la plateforme.<br><br> Êtes vous sûr de vouloir continuer ?`
      }

      if (state == 'Validée') {
        this.message = `Vous êtes sur le point de <b>valider</b> cette mission. Elle sera à présent disponible dans la recherche.<br><br> Êtes vous sûr de vouloir continuer ?`
      }

      if (state == 'Terminée') {
        this.message = `Vous êtes sur le point de passer la mission au statut <b>terminée</b>.<br><br> Les participations <b>en attente de validation</b> seront automatiquement déclinées.`
        if (this.form.participations_count) {
          this.message =
            this.message +
            `<br><br> Une invitation va être envoyée aux participations <b>validées (${this.form.participations_count})</b> invitant les bénévoles à donner leur avis sur la mission effectuée.`
        }
        this.message =
          this.message + `<br><br> Êtes vous sûr de vouloir continuer ?`
      }

      if (state == 'Annulée') {
        this.message = `Attention, vous êtes sur le point d'<b>annuler</b> une mission en lien avec ${this.form.participations_count} participation(s).<br><br> Les participations <b>en attente de validation</b> seront automatiquement annulées et ces bénévoles seront notifiés de l'annulation de la mission.<br><br> Êtes vous sûr de vouloir continuer ?`
      }

      if (state == 'Signalée') {
        this.message = `Vous êtes sur le point de <b>signaler</b> une mission qui ne répond pas aux exigences de la charte ou des règles fixés par le Décret n° 2017-930 du 9 mai 2017 relatif à la Réserve Civique. Le responsable est en lien avec ${this.form.participations_count} bénévole(s). <br><br> Les participations à venir seront automatiquement <b>annulées</b>. Les coordonnées des bénévoles seront masquées.`
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
            .updateMission(this.form.id, this.form)
            .then((response) => {
              let message = 'Le statut de la mission a été mis à jour'
              if (
                response.data.state == 'Validée' &&
                this.form.structure.state == 'En attente de validation'
              ) {
                message = `La mission vient d'être validée.\nAttention, l'organisation qui propose cette mission est toujours en attente de validation`
              }
              this.$message.success({ message })
              this.$emit('updated', response.data)
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
