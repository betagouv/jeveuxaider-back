<template>
  <div>
    <el-menu-item
      index="/dashboard"
      :class="{ 'is-active': isActive('dashboard') }"
    >
      <div v-if="$store.getters.isSidebarExpanded">Tableau de bord</div>

      <i
        v-else
        v-tooltip.right="{
          content: `Tableau de bord`,
          classes: 'bo-style',
        }"
        class="el-icon-data-analysis"
      />
    </el-menu-item>
    <el-menu-item
      v-for="territoire in $store.getters.user.profile.territoires"
      :key="territoire.id"
      :index="`/dashboard/territoire/${territoire.id}`"
      :class="{
        'is-active': doesPathContains(`/dashboard/territoire/${territoire.id}`),
      }"
    >
      <span v-if="$store.getters.isSidebarExpanded">{{ territoire.name }}</span>

      <i
        v-else
        v-tooltip.right="{
          content: territoire.name,
          classes: 'bo-style',
        }"
        class="el-icon-school"
      />
    </el-menu-item>
  </div>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
}
</script>

<style lang="sass" scoped>
::v-deep .el-badge__content.is-fixed
  top: 13px
  right: -5px
</style>
