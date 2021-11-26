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

    <!-- Mon réseau -->
    <nuxt-link
      :to="`/dashboard/reseaux/${reseauId}/edit`"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains(`dashboard/reseaux/${reseauId}/edit`)
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains(`dashboard/reseaux/${reseauId}/edit`)
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="
          require('@/assets/images/icones/heroicon/office-building.svg?raw')
        "
      />
      Mon réseau
    </nuxt-link>

    <!-- Antennes -->
    <nuxt-link
      :to="`/dashboard/reseaux/${$store.getters.profile.tete_de_reseau_id}/structures`"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains(
          `/dashboard/reseaux/${$store.getters.profile.tete_de_reseau_id}/structures`
        )
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
      x-state-description='undefined: "bg-gray-200 text-gray-900", undefined: "text-gray-700 hover:text-gray-900 hover:bg-gray-50"'
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains(
            `/dashboard/reseaux/${$store.getters.profile.tete_de_reseau_id}/structures`
          )
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/library.svg?raw')"
      />
      Antennes
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

    <!-- Missions -->
    <nuxt-link
      :to="`/dashboard/reseaux/${$store.getters.profile.tete_de_reseau_id}/modeles-de-mission`"
      class="group flex items-center px-2 py-2 text-sm rounded-md"
      :class="[
        doesPathContains(
          `/dashboard/reseaux/${$store.getters.profile.tete_de_reseau_id}/modeles-de-mission`
        )
          ? 'bg-primary text-white font-semibold'
          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 font-medium',
      ]"
    >
      <div
        class="mr-3 flex-shrink-0 h-6 w-6"
        :class="[
          doesPathContains(
            `/dashboard/reseaux/${$store.getters.profile.tete_de_reseau_id}/modeles-de-mission`
          )
            ? 'bg-primary text-white'
            : 'text-gray-400 hover:text-gray-900',
        ]"
        v-html="require('@/assets/images/icones/heroicon/color-swatch.svg?raw')"
      />
      Modèles de mission
    </nuxt-link>
  </div>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
  computed: {
    reseauId() {
      return this.$store.state.auth.user.profile.tete_de_reseau_id
    },
  },
}
</script>
