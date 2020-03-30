<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">{{ $store.getters["user/contextRoleLabel"] }}</div>
        <div class="mb-12 font-bold text-2xl text-gray-800">Dashboard</div>
      </div>
    </div>
    <div class="px-12">
      <card-mission-count label="Missions" name="missions" link="/dashboard/missions" />
      <card-participation-count
        label="Participations"
        name="participations"
        link="/dashboard/participations"
      />
    </div>
    <div class="px-12" v-if="$store.getters.contextRole == 'referent'">
      <card-structures-count label="Structures" name="structures" link="/dashboard/structures" />
      <!-- <card-profile-count label="Utilisateurs" name="profiles" link="/dashboard/profiles" /> -->
      <card-analytics label="Départements" name="analytics"></card-analytics>
    </div>
    <div class="px-12" v-if="$store.getters.contextRole == 'admin' || $store.getters.contextRole == 'analyste'">
      <card-structure-count label="Structures" name="structures" link="/dashboard/structures" />
      <card-profile-count label="Utilisateurs" name="profiles" link="/dashboard/profiles" />
      <card-analytics label="Départements" name="analytics"></card-analytics>
    </div>
  </div>
</template>

<script>
import CardMissionCount from "@/components/CardMissionCount";
import CardParticipationCount from "@/components/CardParticipationCount";
import CardStructureCount from "@/components/CardStructureCount";
import CardProfileCount from "@/components/CardProfileCount";
import CardAnalytics from "@/components/CardAnalytics";

export default {
  name: "Dashboard",
  components: {
    CardMissionCount,
    CardParticipationCount,
    CardStructureCount,
    CardProfileCount,
    CardAnalytics
  },
  computed: {}
};
</script>
