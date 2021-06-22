<template>
  <div class="space-y-1">
    <!-- Tableau de bord -->
    <router-link
      to="/dashboard"
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

    <div v-if="$store.getters.user.profile.territoires.length">
      <router-link
        v-for="territoire in $store.getters.user.profile.territoires"
        :key="territoire.id"
        :to="`/dashboard/territoire/${territoire.id}`"
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
        :class="{
          'bg-gray-50': doesPathContains(
            `/dashboard/territoire/${territoire.id}`
          ),
        }"
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
        <div>
          <div class="uppercase text-xxs font-semibold text-gray-500">
            Territoire
          </div>
          <div>{{ territoire.name }}</div>
        </div>
      </router-link>
    </div>

    <div v-if="$store.getters.user.profile.structures.length">
      <router-link
        v-for="structure in $store.getters.user.profile.structures"
        :key="structure.id"
        :to="`/dashboard/structure/${structure.id}`"
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
        :class="{
          'bg-gray-50': doesPathContains(
            `/dashboard/structure/${structure.id}`
          ),
        }"
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
            require('@/assets/images/icones/heroicon/library.svg?include')
          "
        />
        <div>
          <div class="uppercase text-xxs font-semibold text-gray-500">
            Organisation
          </div>
          <div>{{ structure.name }}</div>
        </div>
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
