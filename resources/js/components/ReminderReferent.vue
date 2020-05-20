<template>
  <el-card
    v-if="$store.getters.reminders && $store.getters.reminders.waiting > 0"
    shadow="never"
    class="p-5"
  >
    <div class>
      {{ $store.getters.user.profile.first_name }}, des structures sont en
      attente de validation dans votre département.
    </div>
    <div class="text-gray-400 mt-3 mb-4">
      En validant ces structures, vous leur permettez de publier <br />leurs
      missions sur la plateforme.
    </div>
    <router-link
      :to="{
        name: 'DashboardStructures',
        query: { 'filter[state]': 'En attente de validation' },
      }"
    >
      <el-button type="primary" class="mt-2">
        <template v-if="$store.getters.reminders.waiting > 1">
          Afficher les
          {{ $store.getters.reminders.waiting | formatNumber }} structures
        </template>
        <template v-else>
          Afficher la structure
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
