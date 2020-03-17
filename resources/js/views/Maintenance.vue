<template>
  <div class="not-found flex h-full">
    <div class="flex flex-col justify-center items-center w-1/2">
      <div
        class="flex flex-col h-full justify-center relative"
        style="width: 340px;"
      >
        <img
          src="@/assets/images/logo-snu.png"
          style="max-width: 100px; width:100px; margin-bottom: 45px; margin-top: -70px;"
        />
        <div class="mb-6 font-bold text-2xl text-gray-800">
          Site en maintenance
        </div>
        <div class="text-gray-700 font-light">
          <div class="mb-3">
            Une opération de maintenance est en cours. Nous faisons au plus
            vite. 🚀
          </div>
        </div>
      </div>
    </div>
    <div
      class="flex flex-col justify-center w-1/2"
      style="background-color: #f5f9fc;"
    >
      <div class="flex flex-col items-center">
        <h1
          class="text-2xl font-semibold text-center text-primary"
          style="margin-bottom: 92px;"
        >
          Bientôt de retour
        </h1>

        <img
          src="@/assets/images/groupe-jeunes.png"
          style="max-width: 280px;"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Maintenance"
};
</script>
