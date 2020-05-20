<template>
  <el-card
    v-if="$store.getters.reminders && $store.getters.reminders.waiting"
    shadow="never"
    class="p-5"
  >
    <div
      class
    >
      {{ $store.getters.user.profile.first_name }}, des participations sont en attente de validation dans le cadre de vos missions.
    </div>
    <div class="text-gray-400 mt-3 mb-4">
      En répondant à ces demandes, vous permettez aux bénévoles de suivre
      <br>l'évolution de leur candidature.
    </div>
    <router-link
      :to="{name: 'DashboardParticipations', query: {'filter[state]': 'En attente de validation'}}"
    >
      <el-button
        type="primary"
        class="mt-2"
      >
        <template v-if="$store.getters.reminders.waiting > 1">
          Afficher les {{ $store.getters.reminders.waiting|formatNumber }} participations
        </template>
        <template v-else>
          Afficher la participation
        </template>
      </el-button>
    </router-link>
  </el-card>
</template>

<script>
import { reminders } from "@/api/app";

export default {
  data() {
    return {
      loading: true,
      data: null
    };
  },
  created() {
    this.$store.dispatch('reminders');
  },
  methods: {
    fetchDatas() {
      reminders().then(response => {
        this.loading = false;
        this.data = response.data;
      });
    }
  }
};
</script>