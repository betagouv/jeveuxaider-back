<template>
  <el-card shadow="never" v-if="data && data.waiting" class="p-5">
    <div
      class
    >{{ $store.getters.user.profile.first_name }}, des participations sont en attente de validation dans le cadre de vos missions.</div>
    <div
      class="text-gray-400 mt-3 mb-4"
    >En répondant à ces demandes, vous permettez aux bénévoles de suivre <br>l'évolution de leur candidature.</div>
    <router-link :to="{name: 'Structures', query: {'filter[state]': 'En attente de validation'}}">
      <el-button type="primary" class="mt-2">Afficher les {{ data.waiting|formatNumber }} participations</el-button>
    </router-link>
  </el-card>
</template>

<script>
import { statistics } from "@/api/app";

export default {
  data() {
    return {
      loading: true,
      data: null
    };
  },
  created() {
    this.fetchDatas();
  },
  methods: {
    fetchDatas() {
      statistics("reminder").then(response => {
        this.loading = false;
        this.data = response.data;
      });
    }
  }
};
</script>