<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord - Utilisateurs
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <DashboardTabsMain index="profiles" />
    </div>
    <div class="px-12">
      <DashboardCardProfilesCount
        label="Utilisateurs"
        name="profiles"
        link="/dashboard/profiles"
      />
      <div class="lg:flex">
        <DashboardChartModelsCreated type="profiles" class="w-full lg:w-8/12" />
        <DashboardCardProfilesSkillsCount
          v-if="$store.getters.contextRole == 'admin'"
          name="skills"
          class="w-full mt-6 lg:mt-0 lg:w-4/12 lg:ml-6"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
}
</script>
