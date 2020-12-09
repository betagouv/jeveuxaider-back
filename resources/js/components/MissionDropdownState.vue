<template>
  <div>
    <template v-if="showAskValidation">
      <el-dropdown size="small" split-button :type="type">
        <div style="min-width: 140px; text-align: left">{{ form.state }}</div>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item @click.native="onAskValidationSubmit">
            Publier la mission
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </template>
    <template v-else>
      <el-dropdown v-if="canEditStatut" size="small" split-button :type="type">
        <div style="min-width: 140px; text-align: left">{{ form.state }}</div>
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
            <template v-if="state.value == 'Terminée'"
              >Terminer la mission</template
            >
            <template v-if="state.value == 'Annulée'"
              >Annuler la mission</template
            >
            <template v-if="state.value == 'Signalée'"
              >Signaler la mission</template
            >
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <template v-else>
        <div class="text-sm">{{ form.state }}</div>
      </template>
    </template>
  </div>
</template>

<script>
import { updateMission } from '@/api/mission'

export default {
  name: 'MissionDropdownState',
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
    showAskValidation() {
      return this.$store.getters.contextRole == 'responsable' &&
        this.form.state == 'Brouillon'
        ? true
        : false
    },
    canEditStatut() {
      if (this.$store.getters.contextRole == 'admin') {
        return true
      }
      if (this.form.state == 'Validée') {
        return true
      }
      if (
        this.$store.getters.contextRole == 'referent' ||
        this.$store.getters.contextRole == 'referent_regional'
      ) {
        return ![
          'Signalée',
          'Terminée',
          'Annulée',
          'En attente de validation',
          'Brouillon',
        ].includes(this.form.state)
      }
      return false
    },
    statesAvailable() {
      if (this.$store.getters.contextRole == 'admin') {
        return this.$store.getters.taxonomies.mission_workflow_states.terms.filter(
          (item) => item.value != this.form.state
        )
      } else if (this.$store.getters.contextRole == 'responsable') {
        return this.$store.getters.taxonomies.mission_workflow_states.terms.filter(
          (item) =>
            !['Signalée', 'En attente de validation'].includes(item.value) &&
            item.value != this.form.state
        )
      } else {
        return this.$store.getters.taxonomies.mission_workflow_states.terms.filter(
          (item) =>
            !['En attente de validation'].includes(item.value) &&
            item.value != this.form.state
        )
      }
    },
  },
  methods: {
    onAskValidationSubmit() {
      if (this.form.structure.state != 'Validée') {
        this.$message({
          type: 'error',
          message:
            'Votre organisation doit être validée avant de pouvoir publier une mission',
        })
      } else {
        if (this.form.template_id) {
          this.onSubmitState('Validée')
        } else {
          this.onSubmitState('En attente de validation')
        }
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
        this.message = `Les participations en attente de validation seront automatiquement déclinées et celles validées passeront au statut mission effectuée.<br><br>Les bénévoles seront notifiés de ces modifications.<br><br> Êtes vous sûr de vouloir continuer ?`
      }

      if (state == 'Annulée') {
        this.message = `Attention, vous êtes sur le point d'annuler une mission en lien avec ${this.form.participations_count} participation(s).<br><br> Les participations en attente de validation seront automatiquement annulées et ces bénévoles seront notifiés de l'annulation de la mission.<br><br> Êtes vous sûr de vouloir continuer ?`
      }

      if (state == 'Signalée') {
        this.message = `Vous êtes sur le point de signaler une mission qui ne répond pas aux exigences de la charte ou des règles fixés par le Décret n° 2017-930 du 9 mai 2017 relatif à la Réserve Civique. Le responsable est en lien avec ${this.form.participations_count} bénévole(s). <br><br> Les participations à venir seront automatiquement annulées. Les coordonnées des bénévoles seront masquées.`
      }

      this.$confirm(this.message, 'Changement de statut', {
        confirmButtonText: 'Je confirme',
        cancelButtonText: 'Annuler',
        dangerouslyUseHTMLString: true,
      })
        .then(() => {
          this.form.state = state
          updateMission(this.form.id, this.form)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Le statut de la mission a été mis à jour',
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
