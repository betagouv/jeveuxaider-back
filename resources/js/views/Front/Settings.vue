<template>
  <div>
    <VueHeader />
    <div class="bg-blue-900 pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">Mes paramètres de compte</h1>
        </div>
      </div>
    </div>

    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div
          class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          Formulaire
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueHeader from "@/components/VueHeader";

export default {
  name: "FrontSettings",
  components: {
    VueHeader
  }
};
</script>
