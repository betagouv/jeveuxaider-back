<template>
  <div class="space-y-1">
    <!-- Tableau de bord -->
    <nuxt-link
      to="/dashboard"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        isActive('dashboard')
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
          isActive('dashboard')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/home.svg?include')"
      />
      Tableau de bord
    </nuxt-link>

    <!-- Territoires -->
    <nuxt-link
      to="/dashboard/territoires"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains('dashboard/territoire')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains('dashboard/territoire')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/globe.svg?include')"
      />
      Territoires
    </nuxt-link>

    <!-- Structures -->
    <nuxt-link
      to="/dashboard/structures"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains('dashboard/structure')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains('dashboard/structure')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/library.svg?include')"
      />
      Organisations
    </nuxt-link>

    <!-- Missions -->
    <nuxt-link
      to="/dashboard/missions"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains('dashboard/mission')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains('dashboard/mission')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="
          require('@/assets/images/icones/heroicon/collection.svg?include')
        "
      />
      Missions
    </nuxt-link>

    <!-- Participations -->
    <nuxt-link
      to="/dashboard/participations"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains('dashboard/participation')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains('dashboard/participation')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="
          require('@/assets/images/icones/heroicon/identification.svg?include')
        "
      />
      Participations
    </nuxt-link>

    <!-- Utilisateurs -->
    <nuxt-link
      to="/dashboard/profiles"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains('dashboard/profile')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains('dashboard/profile')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="
          require('@/assets/images/icones/heroicon/user-group.svg?include')
        "
      />
      Utilisateurs
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

    <!-- Contenus -->
    <nuxt-link
      to="/dashboard/contents/releases"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains('dashboard/contents')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains('dashboard/contents')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="
          require('@/assets/images/icones/heroicon/view-list.svg?include')
        "
      />
      Contenus
    </nuxt-link>

    <!-- Activités -->
    <nuxt-link
      to="/dashboard/activities"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains('dashboard/activities')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains('dashboard/activities')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/flag.svg?include')"
      />
      Activités
    </nuxt-link>

    <!-- Topito -->
    <nuxt-link
      to="/dashboard/topito"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains('dashboard/topito')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains('dashboard/topito')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/sparkles.svg?include')"
      />
      Topito
    </nuxt-link>

    <!-- Témoignages -->
    <nuxt-link
      to="/dashboard/temoignages"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains('dashboard/temoignages')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains('dashboard/temoignages')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/chat.svg?include')"
      />
      Témoignages
    </nuxt-link>

    <!-- Corbeille -->
    <nuxt-link
      to="/dashboard/corbeille"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains('dashboard/corbeille')
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains('dashboard/corbeille')
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/trash.svg?include')"
      />
      Corbeille
    </nuxt-link>
  </div>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
}
</script>

<style lang="postcss" scoped>
::v-deep .el-badge__content.is-fixed {
  top: 13px;
  right: -5px;
}
</style>
