<template>
  <el-menu
    :default-active="$router.history.current.path"
    mode="horizontal"
    class="!mb-8"
    @select="$router.push($event)"
  >
    <el-menu-item :index="`/dashboard/profile/${profileId}`">
      Informations
    </el-menu-item>
    <el-menu-item :index="`/dashboard/profile/${profileId}/participations`">
      Participations
    </el-menu-item>
    <el-menu-item :index="`/dashboard/profile/${profileId}/activity`">
      Activités
    </el-menu-item>
    <el-menu-item :index="`/dashboard/profile/${profileId}/history`">
      Historique
    </el-menu-item>
  </el-menu>
</template>

<script>
export default {
  props: {
    profileId: {
      type: Number,
      required: true,
    },
  },
  methods: {
    handleSelect(index) {
      this.$router.push(`${index}`)
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
