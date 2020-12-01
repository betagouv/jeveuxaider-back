<template>
  <div class="mx-auto w-full max-w-3xl p-4">
    <div>
      <h2 class="mt-8 text-3xl leading-tight font-extrabold text-gray-900">
        Votre navigateur est
        <span class="text-blue-800">obsolète</span>
      </h2>
    </div>
    <div class="mt-8 border-t border-gray-200 pt-8" />
    <div class="text-gray-700 font-light">
      <div class="mb-6 font-bold text-2xl text-gray-800">
        Pourquoi ai-je besoin d'un navigateur à jour ?
      </div>
      <div class="mb-3">
        Tout navigateur actualisé vous protègera encore plus face aux arnaques,
        virus, chevaux de Troie, à l'hameçonnage et à toute autre menace. Il
        comblera toute faille de sécurité éventuelle du navigateur utilisé
        jusqu'ici et protègera votre vie privée !
      </div>
      <div class="mb-3">
        Les sites Internet utilisant les nouvelles technologies s'afficheront
        correctement
      </div>
      <div class="mb-3">
        La rapidité et le confort d'utilisation augmentent avec chaque nouvelle
        génération de navigateurs.
      </div>
      <div class="mb-3 mt-12">Sélectionner votre nouveau navigateur</div>
      <div class="mb-12">
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
</template>

<script>
export default {
  name: 'BrowserOutdated',
}
</script>
