<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">{{ $store.getters["user/contextRoleLabel"] }}</div>
        <div class="mb-12 font-bold text-2xl text-gray-800">Tableau de bord - Missions</div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <dashboard-menu index="missions"></dashboard-menu>
    </div>
    <div class="px-12">
      <card-mission-count label="Missions" name="missions" link="/dashboard/missions" />
    </div>
  </div>
</template>

<script>
import DashboardMenu from "@/components/DashboardMenu";
import CardMissionCount from "@/components/CardMissionCount";

export default {
  name: "DashboardMissions",
  components: {
    DashboardMenu,
    CardMissionCount
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
