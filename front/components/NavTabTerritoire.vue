<template>
  <el-menu
    :default-active="$router.history.current.path"
    mode="horizontal"
    class="mb-8"
    @select="$router.push($event)"
  >
    <el-menu-item :index="`/dashboard/territoire/${territoire.id}`">
      Informations
    </el-menu-item>
    <el-menu-item
      v-if="territoire.permissions.canViewStats"
      :index="`/dashboard/territoire/${territoire.id}/statistics`"
    >
      Statistiques
    </el-menu-item>
    <el-menu-item
      v-if="$store.getters.contextRole == 'admin'"
      :index="`/dashboard/territoire/${territoire.id}/history`"
    >
      Historique
    </el-menu-item>
  </el-menu>
</template>

<script>
export default {
  props: {
    territoire: {
      type: Object,
      required: true,
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
