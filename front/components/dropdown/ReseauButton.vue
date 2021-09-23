<template>
  <el-dropdown split-button type="primary" @command="handleCommand">
    <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/edit`">
      Modifier le réseau
    </nuxt-link>
    <el-dropdown-menu slot="dropdown">
      <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/structures/add`">
        <el-dropdown-item :command="{}">
          Ajouter des organisations
        </el-dropdown-item>
      </nuxt-link>
      <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/members`">
        <el-dropdown-item :command="{}"> Gérer les membres </el-dropdown-item>
      </nuxt-link>
      <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/members/add`">
        <el-dropdown-item :command="{}"> Ajouter un membre </el-dropdown-item>
      </nuxt-link>
      <el-dropdown-item
        v-if="$store.getters.contextRole == 'admin'"
        :command="{ action: 'delete' }"
        divided
      >
        Supprimer le réseau
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  props: {
    reseau: {
      type: Object,
      required: true,
    },
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'delete') {
        // this.handleDeleteStructure()
      }
    },
    // handleDeleteStructure() {
    //   if (this.structure.missions_count > 0) {
    //     this.$alert(
    //       'Il est impossible de supprimer une organisation qui contient des missions.',
    //       "Supprimer l'organisation",
    //       {
    //         confirmButtonText: 'Retour',
    //         type: 'warning',
    //         center: true,
    //       }
    //     )
    //   } else {
    //     this.$confirm(
    //       `L'organisation ${this.structure.name} sera définitivement supprimée de la plateforme.<br><br> Voulez-vous continuer ?<br>`,
    //       "Supprimer l'organisation",
    //       {
    //         confirmButtonText: 'Supprimer',
    //         confirmButtonClass: 'el-button--danger',
    //         cancelButtonText: 'Annuler',
    //         center: true,
    //         dangerouslyUseHTMLString: true,
    //         type: 'error',
    //       }
    //     ).then(() => {
    //       this.$api.deleteStructure(this.structure.id).then(() => {
    //         this.$message.success({
    //           message: `L'organisation ${this.structure.name} a été supprimée.`,
    //         })
    //         this.$router.push('/dashboard/structures')
    //       })
    //     })
    //   }
    // },
  },
}
</script>

<style scoped lang="postcss">
.el-menu--horizontal {
  @apply px-12;
  > .el-menu-item {
    border-bottom: solid 3px #070191;
    @apply mr-8 p-0 font-medium;
  }
}
</style>
