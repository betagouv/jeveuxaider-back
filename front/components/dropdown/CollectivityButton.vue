<template>
  <el-dropdown split-button type="primary" @command="handleCommand">
    <nuxt-link :to="`/dashboard/collectivity/${collectivity.id}/edit`">
      Modifier la collectivité
    </nuxt-link>
    <el-dropdown-menu slot="dropdown">
      <nuxt-link
        :to="`/territoires/collectivites/${collectivity.slug}`"
        target="_blank"
      >
        <el-dropdown-item> Visualiser la collectivité</el-dropdown-item>
      </nuxt-link>

      <el-dropdown-item
        v-if="$store.getters.contextRole == 'admin'"
        divided
        :command="{ action: 'delete' }"
      >
        Supprimer la collectivité
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  props: {
    collectivity: {
      type: Object,
      required: true,
    },
  },
  methods: {
    handleCommand(command) {
      if (!command) {
        return
      }
      if (command.action == 'delete') {
        this.handleClickDelete(command.id)
      } else {
        this.$router.push(command)
      }
    },
    handleClickDelete() {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer cette collectivité ?`,
        'Supprimer cette collectivité',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deleteCollectivity(this.collectivity.id).then(() => {
          this.$message.success({
            message: `La collectivité a été supprimée.`,
          })
          this.$router.push('/dashboard/collectivities')
        })
      })
    },
  },
}
</script>

<style scoped lang="sass">
.el-menu--horizontal
  @apply px-12
  > .el-menu-item
    border-bottom: solid 3px #070191
    @apply mr-8 p-0 font-medium
</style>
