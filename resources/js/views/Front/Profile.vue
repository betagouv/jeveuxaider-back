<template>
  <div>
    <vue-header title="Mon profil"></vue-header>
    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div
          class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >FORMULAIRE</div>
      </div>
    </div>
  </div>
</template>

<script>
import VueHeader from "@/components/VueHeader";

export default {
  name: "FrontProfile",
  components: {
    VueHeader
  }
};
</script>
