<template>
  <div class="not-found flex h-full">
    <div class="flex flex-col justify-center items-center w-1/2">
      <div
        class="flex flex-col h-full justify-center relative"
        style="width: 340px;"
      >
        <img
          src="@/assets/images/logo-snu.png"
          style="max-width: 100px; width: 100px; margin-bottom: 45px; margin-top: -70px;"
        />
        <div class="mb-6 font-bold text-2xl text-gray-800">
          404 - Page introuvable
        </div>
        <div class="mb-6 font-light text-sm">
          Cette page n'existe pas ou n'est malheureusement plus disponible.
        </div>
        <div class="">
          <router-link to="/">
            <el-button>Retour à la page d'accueil</el-button>
          </router-link>
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
          La page est introuvable !
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
  name: "NotFound"
};
</script>
