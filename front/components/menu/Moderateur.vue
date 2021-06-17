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

    <!-- Territoires -->
    <router-link
      to="/dashboard/territoires"
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
      :class="{ 'bg-gray-50': doesPathContains('dashboard/territoire') }"
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
        v-html="require('@/assets/images/icones/heroicon/globe.svg?include')"
      />
      Territoires
    </router-link>

    <!-- Structures -->
    <router-link
      to="/dashboard/structures"
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
      :class="{ 'bg-gray-50': doesPathContains('dashboard/structure') }"
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
      Organisations
    </router-link>

    <!-- Missions -->
    <router-link
      to="/dashboard/missions"
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
      :class="{ 'bg-gray-50': doesPathContains('dashboard/mission') }"
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
      Missions
    </router-link>

    <!-- Participations -->
    <router-link
      to="/dashboard/participations"
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
      :class="{ 'bg-gray-50': doesPathContains('dashboard/participation') }"
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

    <!-- Utilisateurs -->
    <router-link
      to="/dashboard/profiles"
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
      :class="{ 'bg-gray-50': doesPathContains('dashboard/profile') }"
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
          require('@/assets/images/icones/heroicon/user-group.svg?include')
        "
      />
      Utilisateurs
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
      <span v-if="$store.getters.user.unreadConversations.length"
        >({{ $store.getters.user.unreadConversations.length }})</span
      >
    </router-link>

    <!-- Contenus -->
    <router-link
      to="/dashboard/contents/releases"
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
      :class="{ 'bg-gray-50': doesPathContains('dashboard/contents') }"
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
          require('@/assets/images/icones/heroicon/view-list.svg?include')
        "
      />
      Contenus
    </router-link>

    <!-- Activités -->
    <router-link
      to="/dashboard/activities"
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
      :class="{ 'bg-gray-50': doesPathContains('dashboard/activities') }"
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
        v-html="require('@/assets/images/icones/heroicon/flag.svg?include')"
      />
      Activités
    </router-link>
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
