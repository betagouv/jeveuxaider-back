<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">{{ $store.getters["user/contextRoleLabel"] }}</div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Nouveautés</div>
      </div>
      <div class></div>
    </div>

    <div v-if="!$store.getters.loading" class="max-w-3xl px-8">
      <div v-for="release in releases" :key="release.id" class="mb-12 bg-gray-100 p-8">
        <div class="p-4">
          <div class="text-lg font-medium text-gray-900">{{ release.title }}</div>
          <div class="text-lg font-medium text-gray-600">{{ release.date|formatMedium }}</div>
        </div>
        <div class="p-4 text-gray-500">
          <div class="release-description" v-html="release.description"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { fetchReleases } from "@/api/app";

export default {
  name: "News",
  data() {
    return {
      loading: true,
      releases: []
    };
  },
  created() {
    this.$store.commit("setLoading", true);
    fetchReleases({ pagination: 0 })
      .then(response => {
        this.releases = response.data.data;
        this.$store.commit("setLoading", false);
        this.loading = false;
      })
      .catch(() => {
        this.loading = false;
      });
  }
};
</script>
