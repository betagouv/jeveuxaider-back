<template>
  <div class="space-y-1">
    <!-- Tableau de bord -->
    <router-link
      :to="`/dashboard/structure/${structureId}/statistics`"
      :class="{ 'bg-gray-50': isActive('dashboard') }"
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

    <!-- Mon organisation -->
    <router-link
      :to="`/dashboard/structure/${structureId}`"
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
      :class="{ 'bg-gray-50': isActive('dashboard/structure') }"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="
          text-gray-400
          group-hover:text-gray-500
          mr-3
          flex-shrink-0
          h-6
          w-6
        "
        v-html="require('@/assets/images/icones/heroicon/library.svg?include')"
      />
      Mon organisation
    </router-link>

    <!-- Missions -->
    <router-link
      :to="`/dashboard/structure/${structureId}/missions`"
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
      :class="{ 'bg-gray-50': isActive('dashboard/mission') }"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="
          text-gray-400
          group-hover:text-gray-500
          mr-3
          flex-shrink-0
          h-6
          w-6
        "
        v-html="
          require('@/assets/images/icones/heroicon/collection.svg?include')
        "
      />
      Mes missions
    </router-link>

    <!-- Participations -->
    <router-link
      :to="`/dashboard/structure/${structureId}/participations`"
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
      :class="{ 'bg-gray-50': isActive('dashboard/participation') }"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="
          text-gray-400
          group-hover:text-gray-500
          mr-3
          flex-shrink-0
          h-6
          w-6
        "
        v-html="
          require('@/assets/images/icones/heroicon/identification.svg?include')
        "
      />
      Participations
    </router-link>

    <!-- Messagerie -->
    <router-link
      to="/messages"
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
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="
          text-gray-400
          group-hover:text-gray-500
          mr-3
          flex-shrink-0
          h-6
          w-6
        "
        v-html="require('@/assets/images/icones/heroicon/mail.svg?include')"
      />
      Messagerie
      <span
        v-if="$store.getters.user.unreadConversations.length"
        class="ml-2 text-xs text-gray-500"
        >({{ $store.getters.user.unreadConversations.length }})</span
      >
    </router-link>
  </div>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
  computed: {
    structureId() {
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
