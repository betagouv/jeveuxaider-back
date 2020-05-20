<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters["user/contextRoleLabel"] }}
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
      <chart-models-created
        type="profiles"
        class="max-w-4xl"
      />
    </div>
  </div>
</template>

<script>
import DashboardMenu from "@/components/DashboardMenu";
import CardProfileCount from "@/components/CardProfileCount";
import ChartModelsCreated from "@/components/charts/ChartModelsCreated";

export default {
  name: "DashboardProfiles",
  components: {
    DashboardMenu,
    CardProfileCount,
    ChartModelsCreated
  },
  data() {
    return {
      activeIndex: "profiles",
      loading: false
    };
  },
  computed: {},
  methods: {}
};
</script>
