<template>
  <el-dropdown split-button type="primary" @command="handleCommand">
    <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/edit`">
      Modifier le réseau
    </nuxt-link>
    <el-dropdown-menu slot="dropdown">
      <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/structures/add`">
        <el-dropdown-item :command="{}">
          Relier des antennes existantes
        </el-dropdown-item>
      </nuxt-link>
      <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/structures/invite`">
        <el-dropdown-item :command="{}">
          Inviter de nouvelles antennes
        </el-dropdown-item>
      </nuxt-link>
      <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/responsables`">
        <el-dropdown-item :command="{}">
          Gérer les responsables
        </el-dropdown-item>
      </nuxt-link>
      <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/responsables/add`">
        <el-dropdown-item :command="{}">
          Inviter un responsable de réseau
        </el-dropdown-item>
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
        this.handleDeleteReseau()
      }
    },
    handleDeleteReseau() {
      if (this.reseau.structures_count > 0) {
        this.$alert(
          'Il est impossible de supprimer un réseau qui contient des antennes.',
          'Supprimer le réseau',
          {
            confirmButtonText: 'Retour',
            type: 'warning',
            center: true,
          }
        )
      } else {
        this.$confirm(
          `Le réseau ${this.reseau.name} sera définitivement supprimé. <br><br> Voulez-vous continuer ?<br>`,
          'Supprimer le réseau',
          {
            confirmButtonText: 'Supprimer',
            confirmButtonClass: 'el-button--danger',
            cancelButtonText: 'Annuler',
            center: true,
            dangerouslyUseHTMLString: true,
            type: 'error',
          }
        ).then(() => {
          this.$api.deleteReseau(this.reseau.id).then(() => {
            this.$message.success({
              message: `Le réseau ${this.reseau.name} a été supprimé.`,
            })
            this.$router.push('/dashboard/reseaux')
          })
        })
      }
    },
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
