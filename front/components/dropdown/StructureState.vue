<template>
  <div>
    <el-dropdown v-if="canEditStatut" :size="size" split-button :type="type">
      <div style="min-width: 140px" class="text-left">
        {{ structure.state }}
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
            >Valider l'organisation</template
          >
          <template v-if="state.value == 'Signalée'"
            >Signaler l'organisation</template
          >
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
    <template v-else>
      <div class="text-sm">{{ structure.state }}</div>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    structure: {
      type: Object,
      required: true,
    },
    size: {
      type: String,
      required: false,
      default: 'small',
    },
  },
  data() {
    return {
      form: { ...this.structure },
      message: 'Êtes vous sur de vos changements ?',
    }
  },
  computed: {
    type() {
      if (this.structure.state == 'En attente de validation') {
        return 'warning'
      }
      return 'default'
    },
    canEditStatut() {
      if (this.$store.getters.contextRole == 'admin') {
        return true
      }
      return !!(
        this.structure.state != 'Signalée' &&
        this.structure.state != 'Désinscrite'
      )
    },
    statesAvailable() {
      if (this.$store.getters.contextRole == 'admin') {
        return this.$store.getters.taxonomies.structure_workflow_states.terms.filter(
          (item) => item.value != this.structure.state
        )
      } else {
        return this.$store.getters.taxonomies.structure_workflow_states.terms.filter(
          (item) =>
            !['En attente de validation', 'Désinscrite'].includes(item.value) &&
            item.value != this.structure.state
        )
      }
    },
  },
  methods: {
    onSubmitState(state) {
      if (state == 'Validée') {
        this.message = `Vous êtes sur le point de <b>valider</b> une organisation.`
        if (this.form.missions_count > 0) {
          this.message += `<br>Toutes ses missions en attente de validation seront également validées.`
        }
      }

      if (state == 'Signalée') {
        this.message = `Vous êtes sur le point de signaler une organisation qui ne répond pas aux exigences de la charte ou des règles fixés par le Décret n° 2017-930 du 9 mai 2017 relatif à la Réserve Civique. L'organisation est en lien avec ${this.form.missions_count} mission(s). <br><br> Les participations à venir seront automatiquement annulées. Les coordonnées des bénévoles seront masquées et une notification d'annulation sera envoyée aux bénévoles initialement inscrits.`
      }

      this.$confirm(this.message, 'Changement de statut', {
        confirmButtonText: 'Je confirme',
        cancelButtonText: 'Annuler',
        dangerouslyUseHTMLString: true,
      })
        .then(() => {
          this.form.state = state
          this.$api
            .updateStructure(this.form.id, this.form)
            .then((response) => {
              this.$message.success({
                message: "Le statut de l'organisation a été mis à jour",
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
