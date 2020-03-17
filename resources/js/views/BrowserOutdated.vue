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
          Pourquoi ai-je besoin d'un navigateur à jour ?
        </div>
        <div class="text-gray-700 font-light">
          <div class="mb-3">
            Tout navigateur actualisé vous protègera encore plus face aux
            arnaques, virus, chevaux de Troie, à l'hameçonnage et à toute autre
            menace. Il comblera toute faille de sécurité éventuelle du
            navigateur utilisé jusqu'ici et protègera votre vie privée !
          </div>
          <div class="mb-3">
            Les sites Internet utilisant les nouvelles technologies
            s'afficheront correctement
          </div>
          <div class="mb-3">
            La rapidité et le confort d'utilisation augmentent avec chaque
            nouvelle génération de navigateurs.
          </div>
          <div class="mb-3 mt-12">
            Sélectionner votre nouveau navigateur
          </div>
          <div class="">
            <a
              href="https://www.google.com/intl/fr_fr/chrome/"
              target="_blank"
              class="mr-2"
            >
              <el-button icon="el-icon-check">Chrome</el-button>
            </a>
            <a
              href="https://www.mozilla.org/fr/firefox/new/"
              target="_blank"
              class="mr-2"
            >
              <el-button icon="el-icon-check">Firefox</el-button>
            </a>
            <a
              href="https://www.microsoft.com/fr-fr/windows/microsoft-edge"
              target="_blank"
              class="mr-2"
            >
              <el-button icon="el-icon-check">Edge</el-button>
            </a>
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
          Votre navigateur est obsolète !
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
  name: "BrowserOutdated"
};
</script>
