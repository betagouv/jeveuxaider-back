<template>
  <el-card
    v-if="$store.getters.reminders && $store.getters.reminders.total > 0"
    shadow="never"
    class="p-5"
  >
    <div class>
      {{ $store.getters.user.profile.first_name }}, des organisations
      <span v-if="$store.getters.reminders.missions > 0"> et des missions</span>
      sont en attente de validation dans votre département.
    </div>
    <div class="text-gray-400 mt-3 mb-4">
      En validant ces organisations
      <span v-if="$store.getters.reminders.missions > 0"> et ces missions</span
      >, vous permettez aux responsables de publier <br />leurs missions sur la
      plateforme.
    </div>
    <router-link
      v-if="$store.getters.reminders.structures > 0"
      :to="{
        name: 'DashboardStructures',
        query: { 'filter[state]': 'En attente de validation' },
      }"
    >
      <el-button type="primary" class="mt-2">
        <template v-if="$store.getters.reminders.structures > 1">
          Afficher les
          {{ $store.getters.reminders.structures | formatNumber }} organisations
        </template>
        <template v-else>
          Afficher l'organisation
        </template>
      </el-button>
    </router-link>
    <router-link
      v-if="$store.getters.reminders.missions > 0"
      :to="{
        name: 'DashboardMissions',
        query: { 'filter[state]': 'En attente de validation' },
      }"
    >
      <el-button type="primary" class="mt-2">
        <template v-if="$store.getters.reminders.missions > 1">
          Afficher les
          {{ $store.getters.reminders.missions | formatNumber }} missions
        </template>
        <template v-else>
          Afficher la mission
        </template>
      </el-button>
    </router-link>
  </el-card>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      data: null,
    }
  },
  created() {
    this.$store.dispatch('reminders')
  },
}
</script>
