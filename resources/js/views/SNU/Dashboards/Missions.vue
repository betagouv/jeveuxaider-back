<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters["user/contextRoleLabel"] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord - Missions
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <dashboard-menu index="missions" />
    </div>
    <div class="px-12">
      <card-mission-count
        label="Missions"
        name="missions"
        link="/dashboard/missions"
      />
      <chart-models-created
        type="missions"
        class="max-w-4xl"
      />
    </div>
  </div>
</template>

<script>
import DashboardMenu from "@/components/DashboardMenu";
import CardMissionCount from "@/components/CardMissionCount";
import ChartModelsCreated from "@/components/charts/ChartModelsCreated";

export default {
  name: "DashboardMissions",
  components: {
    DashboardMenu,
    CardMissionCount,
    ChartModelsCreated
  },
  data() {
    return {
      loading: false
    };
  },
  computed: {},
  methods: {}
};
</script>
