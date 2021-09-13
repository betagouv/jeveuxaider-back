<template>
  <el-menu
    :default-active="$router.history.current.path"
    mode="horizontal"
    class="!mb-8"
    @select="$router.push($event)"
  >
    <el-menu-item :index="`/dashboard/mission/${mission.id}`">
      Informations
    </el-menu-item>
    <el-menu-item :index="`/dashboard/mission/${mission.id}/statistics`">
      Statistiques
    </el-menu-item>
    <el-menu-item :index="`/dashboard/mission/${mission.id}/participations`">
      Participations
      <span class="text-xs text-gray-600">
        ({{ mission.participations_total }})
      </span>
    </el-menu-item>
    <el-menu-item :index="`/dashboard/mission/${mission.id}/temoignages`">
      Témoignages
      <span class="text-xs text-gray-600"> ({{ mission.avis_count }}) </span>
    </el-menu-item>
    <el-menu-item :index="`/dashboard/mission/${mission.id}/history`">
      Historique
    </el-menu-item>
  </el-menu>
</template>

<script>
export default {
  props: {
    mission: {
      type: Object,
      required: true,
    },
  },
}
</script>

<style scoped lang="postcss">
.el-menu--horizontal {
  @apply px-12;
  > .el-menu-item {
    @apply mr-8 p-0 font-medium;
    border-bottom: solid 3px #070191;
  }
}
</style>
