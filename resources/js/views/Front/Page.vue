<template>
  <div>
    <AppHeader />

    <template v-if="!$store.getters.loading">
      <div class="bg-blue-900 pb-32">
        <div class="container mx-auto px-4">
          <div class="pt-10">
            <h1 class="text-3xl font-bold text-white">{{ page.title }}</h1>
          </div>
        </div>
      </div>

      <div class="-mt-32">
        <div class="container mx-auto px-4 my-12">
          <div
            class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16 text-gray-700 items-center"
          >
            <div style="max-width: 680px;" class="mx-auto" v-html="page.description"></div>
          </div>
        </div>
      </div>
    </template>
    <template v-else></template>
    <AppFooter />
  </div>
</template>

<script>
import { getPage } from "@/api/app";

export default {
  name: "FrontMissions",
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      page: null
    };
  },
  created() {
    this.$store.commit("setLoading", true);
    getPage(this.id)
      .then(response => {
        this.$store.commit("setLoading", false);
        this.page = response.data;
      })
      .catch(() => {
        this.loading = false;
      });
  }
};
</script>
