<template>
  <header class="relative z-20">
    <jdma-benevole
      v-if="
        $store.getters.isAppLoaded &&
        $store.getters.isLogged &&
        $store.getters.participationsValidated > 1
      "
    />
    <div class="z-10 relative bg-white shadow-lg">
      <div
        class="flex justify-between items-center px-4 sm:px-6 md:justify-between md:space-x-10"
        :class="$store.getters.isLogged ? ' py-2' : ' py-5'"
      >
        <div class="">
          <div class="flex items-center">
            <img
              class="h-4 lg:h-5 w-auto flex flex-none"
              src="/images/small-logo.svg"
              alt="Logo République Française"
            />

            <router-link to="/" class="flex flex-none">
              <img
                class="h-5 lg:h-6 w-auto mt-0 lg:-mt-1 ml-4 pr-3"
                src="/images/logo-reserve-civique_dark.svg"
                alt="Logo Réserve Civique"
              />
            </router-link>

            <slot name="append-logo"> </slot>
          </div>
        </div>

        <div class="-mr-2 flex items-center md:hidden">
          <button
            v-show="!showMobileMenu"
            id="main-menu"
            type="button"
            class="bg-white shadow-md rounded-lg p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
            aria-haspopup="true"
            @click="showMobileMenu = true"
          >
            <span class="sr-only">Open main menu</span>
            <!-- Heroicon name: menu -->
            <svg
              class="h-6 w-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
          </button>
        </div>
        <template v-if="showMenu">
          <nav
            class="hidden md:flex space-x-4 text-sm lg:text-base lg:space-x-6"
          >
            <router-link
              to="/missions"
              class="leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
            >
              Trouver une mission
            </router-link>

            <router-link
              v-if="!$store.getters.isLogged"
              to="/register/responsable"
              class="leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
            >
              Organisations
            </router-link>

            <router-link
              to="/territoires"
              class="leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
            >
              Territoires
            </router-link>

            <div
              v-click-outside="() => (isOnSinformer = false)"
              class="relative flex-none"
            >
              <button
                type="button"
                class="group inline-flex items-center space-x-1 leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150 flex-none"
                :class="isOnSinformer ? 'text-gray-900' : 'text-gray-500'"
                @mouseover="isOnSinformer = true"
              >
                <span class="flex-none">S'informer</span>
                <svg
                  class="h-5 w-5 group-hover:text-gray-500 group-focus:text-gray-500 transition ease-in-out duration-150 flex-none"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                  :class="isOnSinformer ? 'text-gray-600' : 'text-gray-400'"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>
              <transition
                enter-active-class="transition ease-out duration-200"
                enter-class="opacity-0 translate-y-1"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-1"
              >
                <div
                  v-show="isOnSinformer"
                  class="absolute lg:right-0 xl:right-auto transform mt-3 px-2 w-screen max-w-xs sm:px-0"
                >
                  <div class="rounded-lg shadow-lg">
                    <div class="rounded-lg shadow-xs overflow-hidden">
                      <div
                        class="z-20 relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8"
                      >
                        <a
                          href="https://covid19.reserve-civique.gouv.fr/engagement/actualites/"
                          target="_blank"
                          class="-m-3 p-3 block space-y-1 rounded-md hover:bg-gray-50 transition ease-in-out duration-150"
                        >
                          <p
                            class="sm:text-sm xl:text-base leading-6 font-medium text-gray-900"
                          >
                            Le blog de l'engagement
                          </p>
                        </a>
                        <a
                          href="https://covid19.reserve-civique.gouv.fr/engagement/dispositifs/"
                          target="_blank"
                          class="-m-3 p-3 block space-y-1 rounded-md hover:bg-gray-50 transition ease-in-out duration-150"
                        >
                          <p
                            class="sm:text-sm xl:text-base leading-6 font-medium text-gray-900"
                          >
                            Les dispositifs publics d’engagement civique
                          </p>
                        </a>

                        <a
                          href="/engagement"
                          target="_blank"
                          class="-m-3 p-3 block space-y-1 rounded-md hover:bg-gray-50 transition ease-in-out duration-150"
                        >
                          <p
                            class="sm:text-sm xl:text-base leading-6 font-medium text-gray-900"
                          >
                            Toutes les initiatives solidaires
                          </p>
                        </a>
                        <a
                          href="/regles-de-securite"
                          class="-m-3 p-3 block space-y-1 rounded-md hover:bg-gray-50 transition ease-in-out duration-150"
                        >
                          <p
                            class="sm:text-sm xl:text-base leading-6 font-medium text-gray-900"
                          >
                            Les 5 règles de sécurité des bénévoles
                          </p>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </transition>
            </div>
            <a
              href="https://twitter.com/ReserveCivique"
              target="_blank"
              class="text-base leading-6 font-medium text-gray-500 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150 hidden xl:inline"
            >
              #ChacunPourTous
            </a>
          </nav>
        </template>
        <div class="hidden md:flex items-center justify-end space-x-4">
          <router-link
            v-if="!$store.getters.isLogged"
            to="/login"
            class="inline-flex uppercase items-center justify-center px-2 py-1 text-xs leading-6 font-medium text-gray-500 transition ease-in-out duration-150 hover:text-blue-800"
          >
            Connexion
          </router-link>

          <router-link
            v-if="!$store.getters.isLogged"
            to="/register/volontaire"
            class="inline-flex uppercase items-center justify-center px-4 py-1 text-xs leading-6 font-medium text-white bg-blue-800 rounded-full hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
          >
            Inscription
          </router-link>
          <router-link
            v-if="$store.getters.isLogged && $store.getters.noRole === true"
            to="/user/missions"
            class="sm:text-sm xl:text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150 flex-none"
          >
            Mes missions
          </router-link>
          <router-link
            v-if="
              $store.getters.isLogged &&
              $store.getters.reminders &&
              $store.getters.reminders.total > 0
            "
            to="/dashboard"
            class="hidden lg:block ml-2"
          >
            <el-badge :value="$store.getters.reminders.total" :max="99">
              <svg
                class="text-gray-500 hover:text-gray-900 h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                />
              </svg>
            </el-badge>
          </router-link>

          <router-link
            v-if="$store.getters.isLogged"
            to="/messages"
            class="hidden lg:block text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
          >
            <el-badge
              :value="$store.getters.user.nbUnreadConversations"
              :hidden="!$store.getters.user.nbUnreadConversations"
              :max="99"
            >
              <svg
                alt="Messages"
                class="h-6 w-6 text-gray-500 hover:text-gray-900"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                />
              </svg>
            </el-badge>
          </router-link>

          <router-link
            v-if="
              $store.getters.isLogged &&
              $store.getters.contextRole != 'volontaire'
            "
            to="/dashboard"
            class="text-base leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
          >
            Tableau de bord
          </router-link>
          <dropdown-user v-if="$store.getters.isLogged"></dropdown-user>
        </div>
      </div>

      <transition
        enter-active-class="transition ease-out duration-200"
        enter-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition duration-100 ease-in"
        leave-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
      >
        <div
          v-show="showMobileMenu"
          class="absolute top-0 inset-x-0 px-2 transition transform origin-top-right md:hidden z-20"
        >
          <div
            class="rounded-lg shadow-md bg-white ring-1 ring-black ring-opacity-5 overflow-hidden"
          >
            <div class="px-5 pt-4 flex items-center justify-between">
              <div>
                <img
                  class="h-5 lg:h-6 w-auto mt-0 lg:-mt-1 pr-3"
                  src="/images/logo-reserve-civique_dark.svg"
                  alt=""
                />
              </div>
              <div class="-mr-2">
                <button
                  type="button"
                  class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                  @click="showMobileMenu = false"
                >
                  <span class="sr-only">Close menu</span>
                  <!-- Heroicon name: x -->
                  <svg
                    class="h-6 w-6"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </div>
            </div>
            <div
              v-show="showMobileMenu"
              role="menu"
              aria-orientation="vertical"
              aria-labelledby="main-menu"
            >
              <div class="px-2 pt-2 pb-3" role="none">
                <router-link
                  to="/missions"
                  class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                  role="menuitem"
                  @click.native="showMobileMenu = false"
                  >Trouver une mission</router-link
                >

                <router-link
                  v-if="!$store.getters.isLogged"
                  to="/register/responsable"
                  class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                  role="menuitem"
                  @click.native="showMobileMenu = false"
                  >Organisations</router-link
                >

                <router-link
                  to="/territoires"
                  class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                  role="menuitem"
                  @click.native="showMobileMenu = false"
                  >Territoires</router-link
                >

                <a
                  href="https://covid19.reserve-civique.gouv.fr/engagement/actualites/"
                  class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                  role="menuitem"
                  >S'informer</a
                >

                <a
                  href="https://twitter.com/ReserveCivique"
                  target="_blank"
                  class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-50"
                  role="menuitem"
                  >#ChacunPourTous</a
                >

                <template v-if="$store.getters.isLogged">
                  <router-link
                    to="/user/infos"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                    role="menuitem"
                    @click.native="showMobileMenu = false"
                    >Mon compte</router-link
                  >
                  <router-link
                    to="/messages"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                    role="menuitem"
                    @click.native="showMobileMenu = false"
                    >Mes messages</router-link
                  >
                  <router-link
                    to="/logout"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                    role="menuitem"
                    @click.native="showMobileMenu = false"
                    >Se déconnecter</router-link
                  >
                </template>
                <template v-else>
                  <router-link
                    to="/login"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                    role="menuitem"
                    >Connexion</router-link
                  >
                </template>
              </div>
              <div role="none">
                <router-link
                  v-if="!$store.getters.isLogged"
                  to="/register/volontaire"
                  class="block w-full px-5 py-3 text-center font-medium text-white bg-blue-800 uppercase"
                  role="menuitem"
                >
                  Inscription
                </router-link>
                <router-link
                  v-else
                  to="/user/missions"
                  class="block w-full px-5 py-3 text-center font-medium text-white bg-blue-800 uppercase"
                  role="menuitem"
                  @click.native="showMobileMenu = false"
                >
                  Mes missions
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </header>
</template>

<script>
import JdmaBenevole from '@/components/JdmaBenevole.vue'

export default {
  name: 'AppHeader',
  components: { JdmaBenevole },
  props: {
    showMenu: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      isOnSinformer: false,
      showMobileMenu: false,
    }
  },
  created() {
    if (
      this.$store.getters.isLogged &&
      this.$store.getters.contextRole != 'volontaire'
    ) {
      this.$store.dispatch('reminders')
    }
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
