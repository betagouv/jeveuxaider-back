<template>
  <header>
    <div :class="background">
      <div class="container mx-auto px-4">
        <div
          class="flex flex-wrap items-center justify-center pt-1 lg:pt-0 lg:h-16"
          :class="border"
        >
          <div class="flex-shrink-0 my-4 lg:my-0 order-1">
            <router-link :to="{ name: 'Homepage' }">
              <img
                class="h-6"
                src="/images/logo-header.png"
                alt="Réserve Civique"
              />
            </router-link>
          </div>

          <slot name="menu">
            <div class="mb-3 lg:ml-4 lg:mr-auto lg:mb-0 w-full lg:w-auto order-3">
              <div class="links-wrapper flex flex-wrap items-center justify-center sm:justify-start -m-2">
                <router-link
                  to="/missions"
                  :class="{
                    'bg-blue-700': isCurrentPath('/missions')
                  }"
                  class="m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition focus:bg-gray-700 hover:bg-blue-700"
                  >Trouver une mission</router-link
                >

                <router-link
                  v-if="$store.getters.isLogged && $store.getters.noRole === true"
                  to="/user/missions"
                  :class="{
                    'bg-blue-700': isCurrentPath('/user/missions')
                  }"
                  class="m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition hover:text-white hover:bg-blue-700"
                  >Mes missions</router-link
                >
                <router-link
                  to="/regles-de-securite"
                  class="hidden sm:block m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition hover:text-white hover:bg-blue-700"
                  :class="{
                    'bg-blue-700': isCurrentPath('/regles-de-securite')
                  }"
                  >Règles de sécurité</router-link
                >
                <a href="https://covid19.reserve-civique.gouv.fr/initiatives-solidaires/" class="m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition hover:text-white hover:bg-blue-700">Toutes les initiatives solidaires</a>
              </div>
            </div>
          </slot>



          <div class="order-2 lg:order-3 ml-auto lg:ml-3">
            <div class="flex items-center -m-2">
              <router-link
                v-if="
                  $store.getters.isLogged &&
                    $store.getters.contextRole != 'volontaire'
                "
                to="/dashboard"
                class="hidden lg:block m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition hover:text-white hover:bg-blue-700"
                :class="{
                  'bg-blue-700': isCurrentPath('/dashboard')
                }"
                >Tableau de bord</router-link
              >
              <dropdown-user v-if="$store.getters.isLogged"></dropdown-user>
              <router-link
                v-else
                to="/login"
                class="m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition bg-red-700 hover:bg-red-800"
                >Se connecter</router-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: "AppHeader",
  props: {
    background: {
      type: String,
      default: "bg-blue-900"
    },
    border: {
      type: String,
      default: "border-b border-blue-800"
    }
  },
  methods: {
    isCurrentPath(path) {
      return window.location.pathname === path;
    }
  }
};
</script>

<style lang="sass" scoped>
.links-wrapper
  @screen sm
    transform: translateX(-.5rem)
  @screen lg
    transform: inherit
</style>
