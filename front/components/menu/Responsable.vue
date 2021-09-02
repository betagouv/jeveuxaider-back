<template>
  <div class="space-y-1">
    <!-- Tableau de bord -->
    <nuxt-link
      :to="`/dashboard/structure/${structureId}/statistics`"
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
    </nuxt-link>

    <!-- Mon organisation -->
    <nuxt-link
      :to="`/dashboard/structure/${structureId}`"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        isActive('dashboard-mon-organisation')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium ',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          isActive('dashboard-mon-organisation')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/library.svg?include')"
      />
      Mon organisation
    </nuxt-link>

    <!-- Missions -->
    <nuxt-link
      :to="`/dashboard/structure/${structureId}/missions`"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        isActive('dashboard-mes-missions')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium ',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          isActive('dashboard-mes-missions')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="
          require('@/assets/images/icones/heroicon/collection.svg?include')
        "
      />
      Mes missions
    </nuxt-link>

    <!-- Participations -->
    <nuxt-link
      :to="`/dashboard/structure/${structureId}/participations`"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        isActive('dashboard-mes-participations')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium ',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          isActive('dashboard-mes-participations')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="
          require('@/assets/images/icones/heroicon/identification.svg?include')
        "
      />
      Participations
    </nuxt-link>

    <!-- Messagerie -->
    <nuxt-link
      to="/messages"
      class="text-gray-700 hover:text-gray-900 hover:bg-gray-50 group flex items-center px-2 py-2 text-sm font-medium rounded-md"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6"
        v-html="require('@/assets/images/icones/heroicon/mail.svg?include')"
      />
      Messagerie
      <span
        v-if="$store.getters.user.unreadConversations.length"
        class="ml-2 text-xs text-gray-500"
        >({{ $store.getters.user.unreadConversations.length }})</span
      >
    </nuxt-link>
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

<style lang="postcss" scoped>
::v-deep .el-badge__content.is-fixed {
  top: 13px;
  right: -5px;
}
</style>
