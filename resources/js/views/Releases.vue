/* eslint-disable */
<template>
  <div class="flex flex-col min-h-full items-center" style="background-color: #f5f9fc;">
    <div class="w-full max-w-2xl my-12">
      <div class="header">
        <div class="mb-8 pb-8 border-b">
          <img src="/images/logo-reserve-civique_light.svg" style="max-width: 50px;" />
        </div>
        <div class="header-titles flex-1">
          <div class="mb-12 font-bold text-3xl text-gray-800">Historique des versions</div>
        </div>
      </div>
      <div class="text-gray-600">
        La plateforme de la Réserve Civique est destinée aux référents départementaux et
        aux responsables d'associations. Vous pouvez remonter des axes
        d'améliorations en nous faisant vos retours à l'adresse mail suivante:
        contact@reserve-civique.beta.gouv.fr
      </div>
      <div class="my-12">
        <div v-for="release in releases" :key="release.id" class="bg-white rounded mb-8">
          <div class="border-b p-6">
            <div class="text-primary text-xl mb-1">{{ release.title }}</div>
            <div class="text-gray-600">{{ release.date | formatMedium }}</div>
          </div>
          <div
            class="p-6 font-light wysiwyg-field text-sm text-gray-800"
            v-html="release.description"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { fetchReleases } from "@/api/release";

export default {
  name: "Releases",
  data() {
    return {
      loading: true,
      releases: []
    };
  },
  created() {
    this.$store.commit("setLoading", true);
    fetchReleases()
      .then(response => {
        this.$store.commit("setLoading", false);
        this.releases = response.data;
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {}
};
</script>
