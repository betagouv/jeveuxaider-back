<template>
  <el-dropdown split-button type="primary" @command="handleCommand">
    <nuxt-link :to="`/dashboard/structure/${structure.id}/edit`">
      Modifier l'organisation
    </nuxt-link>
    <el-dropdown-menu slot="dropdown">
      <nuxt-link
        v-if="structure.statut_juridique == 'Association'"
        target="_blank"
        :to="`/organisations/${structure.slug}`"
      >
        <el-dropdown-item :command="{}"
          >Afficher l'organisation</el-dropdown-item
        >
      </nuxt-link>
      <nuxt-link :to="`/dashboard/structure/${structure.id}/members`">
        <el-dropdown-item :command="{}"> Gérer les membres </el-dropdown-item>
      </nuxt-link>
      <nuxt-link :to="`/dashboard/structure/${structure.id}/members/add`">
        <el-dropdown-item :command="{}"> Ajouter un membre </el-dropdown-item>
      </nuxt-link>
      <el-dropdown-item
        v-if="canBeSentToApiEngagement"
        :command="{ action: 'send-api' }"
        divided
      >
        Envoyer à l'API Engagement
      </el-dropdown-item>
      <el-dropdown-item :command="{ action: 'delete' }" divided>
        Supprimer l'organisation
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  props: {
    structure: {
      type: Object,
      required: true,
    },
  },
  computed: {
    canBeSentToApiEngagement() {
      return (
        this.structure.state == 'Validée' &&
        this.structure.rna &&
        this.$store.getters.contextRole == 'admin'
      )
    },
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleDeleteStructure()
      }
      if (command.action == 'send-api') {
        this.handleSendApiEngagement()
      }
    },
    async handleSendApiEngagement() {
      const res = await this.$api.sendStructureToApiEngagement(
        this.structure.id
      )
      if (res) {
        this.$message.success({
          message: `L'organisation ${this.structure.name} a été envoyée à l'API Engagement.`,
        })
      }
    },
    handleDeleteStructure() {
      if (this.structure.missions_count > 0) {
        this.$alert(
          'Il est impossible de supprimer une organisation qui contient des missions.',
          "Supprimer l'organisation",
          {
            confirmButtonText: 'Retour',
            type: 'warning',
            center: true,
          }
        )
      } else {
        this.$confirm(
          `L'organisation ${this.structure.name} sera définitivement supprimée de la plateforme.<br><br> Voulez-vous continuer ?<br>`,
          "Supprimer l'organisation",
          {
            confirmButtonText: 'Supprimer',
            confirmButtonClass: 'el-button--danger',
            cancelButtonText: 'Annuler',
            center: true,
            dangerouslyUseHTMLString: true,
            type: 'error',
          }
        ).then(() => {
          this.$api.deleteStructure(this.structure.id).then(() => {
            this.$message.success({
              message: `L'organisation ${this.structure.name} a été supprimée.`,
            })
            this.$router.push('/dashboard/structures')
          })
        })
      }
    },
  },
}
</script>

<style scoped lang="sass">
.el-menu--horizontal
  @apply px-12
  > .el-menu-item
    @apply mr-8 p-0 font-medium
      border-bottom: solid 3px #070191
</style>
