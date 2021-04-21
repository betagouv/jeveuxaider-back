<template>
  <div class="min-h-screen bg-white flex">
    <div
      class="flex flex-col w-full justify-center items-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24 -mt-32"
    >
      <template v-if="error.statusCode == 404">
        <h2 class="text-blue-800 my-8 text-3xl leading-tight font-extrabold">
          Cette page n'a pas été trouvée
        </h2>
        <p>
          La page que vous souhaitez afficher n'existe pas ou a été modifiée.
        </p>
      </template>
      <template v-else-if="error.statusCode == 403">
        <h2 class="text-blue-800 my-8 text-3xl leading-tight font-extrabold">
          Accès non autorisé
        </h2>
        <div class="mt-8 border-t pt-8" />
        <p>Vous n'êtes pas autorisé à accéder à cette page.</p>
      </template>
      <template v-else>
        <h2 class="my-8 text-3xl leading-tight font-extrabold text-gray-900">
          Ouuppps!
          <span class="text-blue-800"> Erreur {{ error.statusCode }}</span>
        </h2>
        <p>{{ error.message }}</p>
      </template>
      <div class="flex mt-8">
        <div class="inline-flex rounded-md shadow">
          <nuxt-link
            to="/"
            class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-800 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
          >
            Allez sur la page d'accueil
          </nuxt-link>
        </div>
        <div class="ml-3 inline-flex">
          <div
            class="inline-flex cursor-pointer items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-blue-800 border-blue-800 hover:text-blue-800 hover:bg-blue-50 focus:outline-none focus:shadow-outline focus:border-blue-800 transition duration-150 ease-in-out"
            @click="back()"
          >
            Revenir en arrière
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ErrorLayout',
  props: {
    error: {
      type: Object,
      default: null,
    },
  },
  methods: {
    back() {
      window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
    },
  },
}
</script>
