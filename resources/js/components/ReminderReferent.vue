<template>
  <el-card shadow="never" v-if="data && data.waiting" class="p-5">
    <div
      class
    >{{ $store.getters.user.profile.first_name }}, des structures sont en attente de validation dans votre département.</div>
    <div
      class="text-gray-400 mt-3 mb-4"
    >En validant ces structures, vous leur permettez de publier <br>leurs missions sur la plateforme.</div>
    <router-link :to="{name: 'DashboardStructures', query: {'filter[state]': 'En attente de validation'}}">
      <el-button type="primary" class="mt-2">
        <template v-if="data.waiting > 1">Afficher les {{data.waiting|formatNumber}} structures</template>
        <template v-else>Afficher la structure</template>
      </el-button>
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