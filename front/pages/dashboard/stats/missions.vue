<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord - Missions
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <DashboardTabsMain index="missions" />
    </div>
    <div class="px-12">
      <DashboardCardMissionsCount
        label="Missions"
        name="missions"
        link="/dashboard/missions"
      />
      <DashboardChartModelsCreated type="missions" class="max-w-4xl" />
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
}
</script>
