<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">{{ $store.getters["user/contextRoleLabel"] }}</div>
        <div class="mb-12 font-bold text-2xl text-gray-800">Tableau de bord - Utilisateurs</div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <dashboard-menu index="profiles"></dashboard-menu>
    </div>
    <div class="px-12">
      <card-profile-count label="Utilisateurs" name="profiles" link="/dashboard/profiles" />
    </div>
  </div>
</template>

<script>
import DashboardMenu from "@/components/DashboardMenu";
import CardProfileCount from "@/components/CardProfileCount";

export default {
  name: "DashboardProfiles",
  components: {
    DashboardMenu,
    CardProfileCount
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
