<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-12 font-bold text-[1.75rem] text-[#242526]">
          Tableau de bord - Utilisateurs
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <TabsMain index="profiles" />
    </div>
    <div class="px-12">
      <CardProfilesCount
        label="Utilisateurs"
        name="profiles"
        link="/dashboard/profiles"
      />
      <div class="lg:flex">
        <ChartModelsCreated type="profiles" class="w-full lg:w-8/12" />
        <CardProfilesSkillsCount
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
  asyncData({ $api, store, error, params }) {
    if (
      !['admin', 'referent', 'referent_regional', 'analyste'].includes(
        store.getters.contextRole
      )
    ) {
      return error({ statusCode: 403 })
    }
  },
}
</script>
