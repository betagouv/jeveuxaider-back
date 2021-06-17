<template>
  <div class="space-y-1">
    <!-- Tableau de bord -->
    <router-link
      to="/dashboard"
      class="
        text-gray-700
        hover:text-gray-900
        hover:bg-gray-50
        group
        flex
        items-center
        px-2
        py-2
        text-sm
        font-medium
        rounded-md
      "
      :class="{ 'bg-gray-50': isActive('dashboard') }"
      x-state:on="Current"
      x-state:off="Default"
      aria-current="page"
      x-state-description='Current: "bg-gray-200 text-gray-900", Default: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="
          text-gray-400
          hover:text-gray-900
          group-hover:text-gray-900
          mr-3
          flex-shrink-0
          h-6
          w-6
        "
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
}
</script>
