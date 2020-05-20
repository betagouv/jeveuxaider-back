<template>
  <div class="register mx-auto w-full" style="max-width: 700px;">
    <div>
      <router-link to="/">
        <img
          class="h-8 w-auto"
          src="/images/logo-header-dark.png"
          alt="Réserve Civique"
        />
      </router-link>
      <h2 class="mt-8 text-3xl leading-tight font-extrabold text-gray-900">
        Rejoignez la
        <span class="text-blue-800">Réserve Civique</span>
      </h2>
    </div>
    <div class="mt-8 border-t border-gray-200 pt-8" />
    <div>
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        Engagez-vous dans la lutte contre l’épidémie
      </h3>
      <p class="mt-1 text-sm leading-5 text-gray-500">
        <router-link to="/regles-de-securite">
          Cet engagement nécessite un respect strict des règles sanitaires
          applicables.
        </router-link>
      </p>
    </div>
    <div class="my-8">
      <div class="flex flex-wrap -m-4">
        <div
          class="w-full sm:w-64 border p-8 m-4 rounded-lg flex flex-col text-center hover:border-primary"
          style="height: 270px;"
        >
          <div class="mb-4 uppercase">
            Appel aux Citoyens
          </div>
          <div class="text-gray-600 mb-4 flex-1">
            Je suis volontaire pour aider
          </div>
          <router-link to="/register/volontaire">
            <el-button type="primary">
              Choisir
            </el-button>
          </router-link>
        </div>
        <div
          class="w-full sm:w-64 border p-8 m-4 rounded-lg flex flex-col text-center hover:border-primary"
          style="height: 270px;"
        >
          <div class="mb-4 uppercase">
            Appel aux organismes
          </div>
          <div class="text-gray-600 mb-4 flex-1">
            Je suis une structure publique ou associative
          </div>
          <router-link to="/register/responsable">
            <el-button type="primary">
              Choisir
            </el-button>
          </router-link>
        </div>
      </div>
    </div>

    <div class="">
      <div class="relative">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-300" />
        </div>
        <div class="relative flex justify-center text-sm leading-5">
          <span class="px-2 bg-white text-gray-500">OU</span>
        </div>
      </div>
      <div class="mt-6 sm:col-span-">
        <router-link to="/login">
          <span class="block w-full rounded-md shadow-sm">
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent text-s font-medium rounded-md border border-gray-300 rounded-md bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out"
            >
              J'ai déjà un compte
            </button>
          </span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Register',
}
</script>
