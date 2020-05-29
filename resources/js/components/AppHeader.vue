<template>
  <header>
    <div class="bg-white">
      <div class="container mx-auto">
        <div
          class="flex flex-wrap items-center justify-center pt-1 lg:pt-0 lg:h-16"
        >
          <div class="flex flex-shrink-0 my-4 lg:my-0 lg:mr-3 order-1">
            <img
              class="h-6 mr-3"
              src="/images/marianne.svg"
              alt="Réserve Civique"
            />
            <router-link :to="{ name: 'Homepage' }">
              <img
                class="h-6"
                src="/images/logo-reserve-civique_dark.svg"
                alt="Réserve Civique"
              />
            </router-link>
          </div>

          <slot name="menu">
            <div
              class="mb-3 lg:ml-4 lg:mr-auto lg:mb-0 w-full lg:w-auto order-3"
            >
              <div class="flex space-x-10">
                <router-link
                  to="/missions"
                  class="text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                >
                  Trouver une mission
                </router-link>

                <router-link
                  v-if="
                    $store.getters.isLogged && $store.getters.noRole === true
                  "
                  to="/user/missions"
                  class="text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                >
                  Mes missions
                </router-link>
                <router-link
                  to="/regles-de-securite"
                  class="hidden sm:block text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                >
                  Règles de sécurité
                </router-link>
                <a
                  href="https://covid19.reserve-civique.gouv.fr/initiatives-solidaires/"
                  target="_blank"
                  class="text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                  >Initiatives solidaires</a
                >
                <a
                  href="https://covid19.reserve-civique.gouv.fr/engagement"
                  target="_blank"
                  class="text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                  >Actualités</a
                >
              </div>
            </div>
          </slot>

          <div class="order-2 lg:order-3 ml-auto lg:ml-3">
            <div class="flex items-center">
              <div
                v-if="
                  $store.getters.isLogged &&
                  $store.getters.contextRole != 'volontaire'
                "
                class="flex items-center"
              >
                <router-link
                  to="/dashboard"
                  class="hidden lg:block mr-5 text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                >
                  Tableau de bord
                </router-link>
                <el-badge
                  v-if="
                    $store.getters.isLogged &&
                    $store.getters.reminders &&
                    $store.getters.reminders.waiting > 0
                  "
                  :value="$store.getters.reminders.waiting"
                  :max="99"
                  class="ml-3 mr-5"
                >
                  <router-link to="/dashboard">
                    <i class="el-icon-bell text-white text-2xl" />
                  </router-link>
                </el-badge>
              </div>
              <dropdown-user v-if="$store.getters.isLogged" />
              <router-link
                v-else
                to="/login"
                class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-transparent border border-gray-300 text-sm leading-6 font-medium rounded-full text-gray-500 hover:bg-blue-800 hover:border-blue-800 hover:text-white transition ease-in-out duration-150"
              >
                Se connecter
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'AppHeader',
  created() {
    if (this.$store.getters.isLogged) {
      this.$store.dispatch('reminders')
    }
  },
  methods: {
    isCurrentPath(path) {
      return window.location.pathname === path
    },
  },
}
</script>

<style lang="sass" scoped>
.links-wrapper
  @screen sm
    transform: translateX(-.5rem)
  @screen lg
    transform: inherit
</style>
