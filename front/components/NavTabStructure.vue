<template>
  <el-menu
    :default-active="$router.history.current.path"
    mode="horizontal"
    class="my-8"
    @select="$router.push($event)"
  >
    <el-menu-item :index="`/dashboard/structure/${structure.id}`">
      Informations
    </el-menu-item>
    <el-menu-item :index="`/dashboard/structure/${structure.id}/statistics`">
      Statistiques
    </el-menu-item>
    <el-menu-item :index="`/dashboard/structure/${structure.id}/missions`">
      Missions
      <span class="text-xs text-gray-600"
        >({{ structure.missions_count }})</span
      >
    </el-menu-item>
    <el-menu-item
      :index="`/dashboard/structure/${structure.id}/participations`"
    >
      Participations
      <span class="text-xs text-gray-600"
        >({{ structure.participations_count }})</span
      >
    </el-menu-item>
    <el-menu-item :index="`/dashboard/structure/${structure.id}/history`">
      Historique
    </el-menu-item>
  </el-menu>
</template>

<script>
export default {
  props: {
    structure: {
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
