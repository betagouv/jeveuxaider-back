<template>
  <div class="dashboard">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters["user/contextRoleLabel"] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Dashboard
        </div>
      </div>
    </div>
    <div class="mb-6 px-12 flex flex-wrap">
      <div
        :class="{
          'w-3/4': canSeeStructuresAndProfiles,
          'w-full': !canSeeStructuresAndProfiles
        }"
      >
        <card-mission-count label="Missions" name="missions" link="/dashboard/missions" />
        <card-participation-count label="Participations" name="participations" link="/dashboard/participations" />
      </div>
      <div
        v-if="canSeeStructuresAndProfiles"
        :class="{ 'w-1/4': canSeeStructuresAndProfiles }"
      >
        <card-count label="Structures" name="structures" link="/dashboard/structures" />
        <card-count label="Utilisateurs" name="profiles" link="/dashboard/profiles" />
      </div>
    </div>
    <div class="mb-12 px-12" v-if="$store.getters.contextRole == 'admin' || $store.getters.contextRole == 'referent'">
      <card-analytics label="Départements" name="analytics"></card-analytics>
    </div>
  </div>
</template>

<script>
import CardCount from "@/components/CardCount";
import CardMissionCount from "@/components/CardMissionCount";
import CardParticipationCount from "@/components/CardParticipationCount";
import CardAnalytics from "@/components/CardAnalytics";

export default {
  name: "Dashboard",
  components: {
    CardCount,
    CardMissionCount,
    CardParticipationCount,
    CardAnalytics
  },
  computed: {
    canSeeStructuresAndProfiles() {
      return this.$store.getters.contextRole !== "responsable" &&
        this.$store.getters.contextRole !== "tuteur"
        ? true
        : false;
    }
  }
};
</script>
