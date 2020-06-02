<template>
  <header>
    <div class="bg-white">
      <div class="container mx-auto">
        <div
          class="flex px-3 lg:px-0 flex-wrap items-center justify-between pt-1 lg:pt-0 lg:h-16"
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
              class="mb-3 hidden lg:block lg:ml-4 lg:mr-auto lg:mb-0 w-full lg:w-auto lg:order-2"
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
                <a
                  href="https://covid19.reserve-civique.gouv.fr/initiatives-solidaires/"
                  target="_blank"
                  class="text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                  >Initiatives solidaires</a
                >
                <a
                  href="https://covid19.reserve-civique.gouv.fr/engagement/actualites/"
                  target="_blank"
                  class="text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                  >Actualités</a
                >
              </div>
            </div>

            <div
              v-show="showMobileMenu"
              id="mobile-menu"
              class="lg:ml-4 lg:mr-auto lg:mb-0 w-full lg:w-auto order-5 lg:hidden"
            >
              <div class="flex flex-col text-center">
                <router-link
                  to="/missions"
                  class="py-2 border-t border-gray-200 text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                >
                  Trouver une mission
                </router-link>

                <router-link
                  v-if="
                    $store.getters.isLogged && $store.getters.noRole === true
                  "
                  to="/user/missions"
                  class="py-2 border-t border-gray-200 text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                >
                  Mes missions
                </router-link>
                <a
                  href="https://covid19.reserve-civique.gouv.fr/initiatives-solidaires/"
                  target="_blank"
                  class="py-2 border-t border-gray-200 text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                  >Initiatives solidaires</a
                >
                <a
                  href="https://covid19.reserve-civique.gouv.fr/engagement"
                  target="_blank"
                  class="py-2 border-t border-gray-200 text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                  >Actualités</a
                >
                <router-link
                  v-if="!$store.getters.isLogged"
                  to="/login"
                  class="py-2 border-t border-gray-200 text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                >
                  Se connecter
                </router-link>
              </div>
            </div>
          </slot>

          <div class="order-3 ml-1 lg:ml-3">
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
                  class="hidden lg:block ml-2 mr-3"
                >
                  <router-link to="/dashboard">
                    <i
                      class="el-icon-bell text-gray-500 hover:text-gray-900 text-2xl"
                    />
                  </router-link>
                </el-badge>
              </div>
              <dropdown-user v-if="$store.getters.isLogged" />
              <router-link
                v-else
                to="/login"
                class="hidden lg:block inline-flex items-center justify-center px-4 py-2 rounded-md border border-transparent border border-gray-300 text-sm leading-6 font-medium rounded-full text-gray-500 hover:bg-blue-800 hover:border-blue-800 hover:text-white transition ease-in-out duration-150"
              >
                Se connecter
              </router-link>
            </div>
          </div>

          <div class="block lg:hidden order-4">
            <el-button type="primary" circle @click="toggleMenu">
              <img src="/images/burger-menu.svg" style="width: 16px;" />
            </el-button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'AppHeader',
  data() {
    return {
      showMobileMenu: false,
    }
  },
  created() {
    if (this.$store.getters.isLogged) {
      this.$store.dispatch('reminders')
    }
  },
  methods: {
    toggleMenu() {
      this.showMobileMenu = !this.showMobileMenu
    },
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
