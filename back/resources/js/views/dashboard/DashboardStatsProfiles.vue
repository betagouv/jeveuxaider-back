<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord - Utilisateurs
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <dashboard-menu index="profiles" />
    </div>
    <div class="px-12">
      <card-profile-count
        label="Utilisateurs"
        name="profiles"
        link="/dashboard/profiles"
      />
      <div class="lg:flex">
        <chart-models-created type="profiles" class="w-full lg:w-8/12" />
        <card-skill-count
          v-if="$store.getters.contextRole == 'admin'"
          name="skills"
          class="w-full mt-6 lg:mt-0 lg:w-4/12 lg:ml-6"
        ></card-skill-count>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardMenu from '@/components/DashboardMenu'
import CardProfileCount from '@/components/CardProfileCount'
import ChartModelsCreated from '@/components/charts/ChartModelsCreated'
import CardSkillCount from '@/components/CardSkillCount'

export default {
  name: 'DashboardStatsProfiles',
  components: {
    DashboardMenu,
    CardProfileCount,
    ChartModelsCreated,
    CardSkillCount,
  },
  data() {
    return {
      activeIndex: 'profiles',
      loading: false,
    }
  },
  computed: {},
  methods: {},
}
</script>
