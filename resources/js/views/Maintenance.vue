<template>
<div class="min-h-screen bg-white flex" >
    <div
      class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24"
    >
      <div>
        <img class="h-8 w-auto" src="/images/logo-reserve-civique_dark.svg" alt="Workflow" />
        <h2 class="mt-8 text-3xl leading-tight font-extrabold text-gray-900">
          Site en  
          <span class="text-blue-800">maintenance</span>
        </h2>
      </div>
      <div class="mt-8 border-t border-gray-200 pt-8"></div>
      <div class="">
        <p>Une opération de maintenance est en cours. Nous faisons au plus
            vite. 🚀</p>
      </div>
    </div>
    <div class="hidden lg:block relative w-0 flex-1">
      <img class="absolute inset-0 h-full w-full object-cover" src="/images/bg_header_home.jpg" alt />
    </div>
  </div>
</template>

<script>
export default {
  name: "Maintenance"
};
</script>
