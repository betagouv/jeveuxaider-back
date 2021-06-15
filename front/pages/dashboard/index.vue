<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-12 font-bold text-2-5xl text-gray-800">
          Tableau de bord
        </div>
      </div>
    </div>

    <template v-if="$store.getters.contextRole === 'responsable_territoire'">
      <DashboardTerritoire />
    </template>

    <template v-else>
      <DashboardOther />
    </template>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
}
</script>
