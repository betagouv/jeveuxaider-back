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
        v-html="require('@/assets/images/icones/heroicon/home.svg?raw')"
      />
      Tableau de bord
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
        v-html="require('@/assets/images/icones/heroicon/library.svg?raw')"
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
        v-html="require('@/assets/images/icones/heroicon/collection.svg?raw')"
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
          require('@/assets/images/icones/heroicon/identification.svg?raw')
        "
      />
      Participations
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
        v-html="require('@/assets/images/icones/heroicon/chat.svg?raw')"
      />
      Témoignages
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
        v-html="require('@/assets/images/icones/heroicon/user-group.svg?raw')"
      />
      Utilisateurs
    </nuxt-link>
  </div>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
}
</script>
