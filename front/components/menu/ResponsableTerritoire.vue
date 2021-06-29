<template>
  <div class="space-y-1">
    <!-- Tableau de bord -->
    <router-link
      :to="`/dashboard/territoire/${territoireId}/statistics`"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        isActive('dashboard-statistics')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium ',
      ]"
      x-state:on="Current"
      x-state:off="Default"
      aria-current="page"
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          isActive('dashboard-statistics')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/home.svg?include')"
      />
      Tableau de bord
    </router-link>
  </div>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
  computed: {
    territoireId() {
      return this.$store.state.auth.user.contextable_id
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-badge__content.is-fixed
  top: 13px
  right: -5px
</style>
