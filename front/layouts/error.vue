<template>
  <div class="min-h-screen bg-white flex">
    <div
      class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 -mt-32"
    >
      <template v-if="error.statusCode == 404">
        <h2
          class="text-blue-800 mt-8 text-3xl leading-tight font-extrabold text-gray-900"
        >
          Cette page n'a pas été trouvée
        </h2>
        <div class="mt-8 border-t border-gray-200 pt-8" />
        <p>
          La page que vous souhaitez afficher n'existe pas ou a été modifiée.
        </p>
      </template>
      <template v-else-if="error.statusCode == 403">
        <h2
          class="text-blue-800 mt-8 text-3xl leading-tight font-extrabold text-gray-900"
        >
          Accès non autorisé
        </h2>
        <div class="mt-8 border-t border-gray-200 pt-8" />
        <p>Vous n'êtes pas autorisé à accéder à cette page.</p>
      </template>
      <template v-else>
        <h2 class="mt-8 text-3xl leading-tight font-extrabold text-gray-900">
          Ouuppps!
          <span class="text-blue-800"> Erreur {{ error.statusCode }}</span>
        </h2>
        <div class="mt-8 border-t border-gray-200 pt-8" />
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
    <div class="hidden lg:block relative w-0 flex-1">
      <img
        class="absolute inset-0 h-full w-full object-cover"
        src="@/assets/images/bg_header_home.jpg"
        alt
      />
    </div>
  </div>
</template>

<script>
export default {
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
