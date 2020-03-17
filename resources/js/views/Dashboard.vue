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
    <div class="mb-12 px-12 flex flex-wrap">
      <div
        :class="{
          'w-3/4': canSeeStructuresAndProfiles,
          'w-full': !canSeeStructuresAndProfiles
        }"
      >
        <card-mission-count label="Missions" name="missions" link="missions" />
        <card-young-count label="Volontaires" name="youngs" link="youngs" />
      </div>
      <div
        v-if="canSeeStructuresAndProfiles"
        :class="{ 'w-1/4': canSeeStructuresAndProfiles }"
      >
        <card-count label="Structures" name="structures" link="structures" />
        <card-count label="Utilisateurs" name="profiles" link="profiles" />
      </div>
    </div>
  </div>
</template>

<script>
import CardCount from "../components/CardCount";
import CardMissionCount from "../components/CardMissionCount";
import CardYoungCount from "../components/CardYoungCount";

export default {
  name: "Dashboard",
  components: {
    CardCount,
    CardMissionCount,
    CardYoungCount
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
