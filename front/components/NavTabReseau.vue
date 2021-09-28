<template>
  <el-menu
    :default-active="$router.history.current.path"
    mode="horizontal"
    class="!my-8"
    @select="$router.push($event)"
  >
    <el-menu-item :index="`/dashboard/reseaux/${reseau.id}`">
      Informations
    </el-menu-item>
    <el-menu-item :index="`/dashboard/reseaux/${reseau.id}/structures`">
      Antennes
      <span class="text-xs text-gray-600">({{ reseau.structures_count }})</span>
    </el-menu-item>
    <el-menu-item :index="`/dashboard/reseaux/${reseau.id}/invitations`">
      Invitations
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
