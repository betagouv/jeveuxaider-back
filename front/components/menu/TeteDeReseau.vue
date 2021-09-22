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

    <!-- Structures -->
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
        v-html="require('@/assets/images/icones/heroicon/library.svg?include')"
      />
      Organisations
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
        v-html="
          require('@/assets/images/icones/heroicon/collection.svg?include')
        "
      />
      Modèles de mission
    </nuxt-link>
  </div>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
}
</script>
