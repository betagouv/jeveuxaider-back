<template>
  <header>
    <div class="bg-blue-900">
      <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center justify-center md:h-16 border-b border-blue-800">
          <div class="flex-shrink-0 my-4 md:my-0 order-1">
            <router-link to="/">
              <img class="h-6" src="/images/logo-reserve-civique_light.svg" alt="Réserve Civique" />
            </router-link>
          </div>

          <div class="mb-3 md:ml-auto md:mb-0 w-full md:w-auto order-3">
            <div class="flex flex-wrap items-center justify-center -m-2">
              <router-link
                to="/missions"
                :class="{
                  'bg-blue-700': isCurrentPath('/missions')
                }"
                class="m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition focus:bg-gray-700 hover:bg-blue-700"
              >Trouver une mission</router-link>

              <router-link
                v-if="$store.getters.isLogged"
                to="/user/missions"
                :class="{
                  'bg-blue-700': isCurrentPath('/user/missions')
                }"
                class="m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition hover:text-white hover:bg-blue-700"
              >Mes missions</router-link>
              <router-link
                to="/regles-de-securite"
                class="m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition hover:text-white hover:bg-blue-700"
                :class="{
                  'bg-blue-700': isCurrentPath('/regles-de-securite')
                }"
              >Règles de sécurité</router-link>
            </div>
          </div>
          <div class="order-2 md:order-3 ml-auto md:ml-3">
            <dropdown-user v-if="$store.getters.isLogged"></dropdown-user>
            <router-link
              v-else
              to="/login"
              class="m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition bg-red-700 hover:bg-red-800"
            >Se connecter</router-link>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: "AppHeader",
  methods: {
    isCurrentPath(path) {
      return window.location.pathname === path;
    }
  }
};
</script>
