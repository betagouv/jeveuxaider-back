<template>
  <el-menu
    :default-active="$router.history.current.path"
    mode="horizontal"
    class="!mb-8"
    @select="$router.push($event)"
  >
    <el-menu-item
      v-if="$store.getters.contextRole == 'admin'"
      :index="`/dashboard/reseaux/${reseau.id}`"
    >
      Informations
    </el-menu-item>
    <el-menu-item
      v-if="$store.getters.contextRole == 'admin'"
      :index="`/dashboard/reseaux/${reseau.id}/responsables`"
    >
      Responsables
      <span class="text-xs text-gray-600"
        >({{ reseau.responsables_count }})</span
      >
    </el-menu-item>
    <el-menu-item :index="`/dashboard/reseaux/${reseau.id}/structures`">
      Antennes
      <span class="text-xs text-gray-600">({{ reseau.structures_count }})</span>
    </el-menu-item>
    <el-menu-item
      v-if="$store.getters.contextRole == 'admin'"
      :index="`/dashboard/reseaux/${reseau.id}/missions`"
    >
      Missions
      <span class="text-xs text-gray-600">({{ reseau.missions_count }})</span>
    </el-menu-item>
    <el-menu-item
      v-if="$store.getters.contextRole == 'admin'"
      :index="`/dashboard/reseaux/${reseau.id}/modeles-de-mission`"
    >
      Modèles de mission
      <span class="text-xs text-gray-600"
        >({{ reseau.mission_templates_count }})</span
      >
    </el-menu-item>
    <el-menu-item :index="`/dashboard/reseaux/${reseau.id}/invitations`">
      Invitations à créer une antenne
      <span class="text-xs text-gray-600"
        >({{ reseau.invitations_antennes_count }})</span
      >
    </el-menu-item>
  </el-menu>
</template>

<script>
export default {
  props: {
    reseau: {
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
