<template>
  <header>
    <jdma-benevole
      v-if="
        $store.getters.isAppLoaded &&
        $store.getters.isLogged &&
        $store.getters.participationsValidated > 1
      "
    />
    <div class="z-10 relative bg-white shadow-sm">
      <div class="relative z-10 shadow">
        <!-- flex px-4 justify-between items-center py-5 sm:py-4 md:justify-start
        md:space-x-7 -->

        <div
          class="flex px-3 lg:px-0 flex-wrap items-center justify-between pt-1 lg:pt-0 lg:h-16"
        >
          <div class="flex items-center">
            <img
              class="ml-4 mr-6 h-4 w-auto"
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
              class="hidden lg:flex-1 lg:flex lg:items-center lg:justify-between lg:mx-12"
            >
              <div class="flex space-x-10">
                <router-link
                  to="/missions"
                  class="text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                >
                  Trouver une mission
                </router-link>
                <router-link
                  to="/territoires"
                  class="text-base leading-6 font-medium text-gray-500 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                >
                  Territoires
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
                <div
                  v-click-outside="() => (isOnDomains = false)"
                  class="relative"
                >
                  <button
                    type="button"
                    class="group inline-flex items-center space-x-2 text-base leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                    :class="isOnDomains ? 'text-gray-900' : 'text-gray-500'"
                    @mouseover="menuActive('domains')"
                  >
                    <span>Domaines d'actions</span>
                    <svg
                      class="h-5 w-5 group-hover:text-gray-500 group-focus:text-gray-500 transition ease-in-out duration-150"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      :class="isOnDomains ? 'text-gray-600' : 'text-gray-400'"
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
                      v-show="isOnDomains"
                      class="absolute transform mt-3 px-2 w-screen max-w-xs sm:px-0"
                    >
                      <div class="rounded-lg shadow-lg">
                        <div class="rounded-lg shadow-xs overflow-hidden">
                          <div
                            class="z-20 relative bg-white px-5 py-6 sm:gap-8 sm:p-8 lg:grid-cols-2"
                          >
                            <a
                              href="/thematiques/solidarite-et-insertion"
                              target="_blank"
                              class="-m-3 p-3 flex mb-2 items-start space-x-4 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150"
                            >
                              <div
                                class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-red-600 text-white sm:h-12 sm:w-12"
                              >
                                <img
                                  class="h-7"
                                  src="/images/vivre-ensemble-white.svg"
                                  alt=""
                                />
                              </div>

                              <p
                                class="text-base mt-2 leading-8 font-medium text-gray-900"
                              >
                                Solidarité et insertion
                              </p>
                            </a>
                            <a
                              href="/thematiques/education-pour-tous"
                              target="_blank"
                              class="-m-3 p-3 flex mb-2 items-start space-x-4 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150"
                            >
                              <div
                                class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-blue-900 text-white sm:h-12 sm:w-12"
                              >
                                <img
                                  class="h-7"
                                  src="/images/education-white.svg"
                                  alt=""
                                />
                              </div>

                              <p
                                class="text-base mt-2 leading-8 font-medium text-gray-900"
                              >
                                Éducation pour tous
                              </p>
                            </a>
                            <a
                              href="/thematiques/protection-de-la-nature"
                              target="_blank"
                              class="-m-3 p-3 flex items-start space-x-4 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150"
                            >
                              <div
                                class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-green-700 text-white sm:h-12 sm:w-12"
                              >
                                <img
                                  class="h-7"
                                  src="/images/treehouse-white.svg"
                                  alt=""
                                />
                              </div>

                              <p
                                class="text-base mt-2 leading-8 font-medium text-gray-900"
                              >
                                Protection de la nature
                              </p>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </transition>
                </div>
                <div
                  v-click-outside="() => (isOnSinformer = false)"
                  class="relative"
                >
                  <button
                    type="button"
                    class="group inline-flex items-center space-x-2 text-base leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                    :class="isOnSinformer ? 'text-gray-900' : 'text-gray-500'"
                    @mouseover="menuActive('sinformer')"
                  >
                    <span>S'informer</span>
                    <svg
                      class="h-5 w-5 group-hover:text-gray-500 group-focus:text-gray-500 transition ease-in-out duration-150"
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
                      class="absolute transform mt-3 px-2 w-screen max-w-xs sm:px-0"
                    >
                      <div class="rounded-lg shadow-lg">
                        <div class="rounded-lg shadow-xs overflow-hidden">
                          <div
                            class="z-20 relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8"
                          >
                            <a
                              href="/engagement/actualites"
                              target="_blank"
                              class="-m-3 p-3 block space-y-1 rounded-md hover:bg-gray-50 transition ease-in-out duration-150"
                            >
                              <p
                                class="text-base leading-6 font-medium text-gray-900"
                              >
                                Le blog de l'engagement
                              </p>
                            </a>
                            <a
                              href="/engagement/dispositifs"
                              target="_blank"
                              class="-m-3 p-3 block space-y-1 rounded-md hover:bg-gray-50 transition ease-in-out duration-150"
                            >
                              <p
                                class="text-base leading-6 font-medium text-gray-900"
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
                                class="text-base leading-6 font-medium text-gray-900"
                              >
                                Toutes les initiatives solidaires
                              </p>
                            </a>
                            <a
                              href="/regles-de-securite"
                              class="-m-3 p-3 block space-y-1 rounded-md hover:bg-gray-50 transition ease-in-out duration-150"
                            >
                              <p
                                class="text-base leading-6 font-medium text-gray-900"
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
                  href="https://covid19.reserve-civique.gouv.fr/engagement/actualites/"
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

          <div class="order-3 ml-1 lg:ml-3 flex items-center">
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
                    $store.getters.reminders.total > 0
                  "
                  :value="$store.getters.reminders.total"
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
            <div class="block lg:hidden order-4 ml-3">
              <el-button type="primary" circle @click="toggleMenu">
                <img src="/images/burger-menu.svg" style="width: 16px" />
              </el-button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import JdmaBenevole from '@/components/JdmaBenevole.vue'

export default {
  name: 'AppHeader',
  components: { JdmaBenevole },
  data() {
    return {
      isOnDomains: false,
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
  methods: {
    toggleMenu() {
      this.showMobileMenu = !this.showMobileMenu
    },
    isCurrentPath(path) {
      return window.location.pathname === path
    },
    menuActive(menu) {
      if (menu == 'sinformer') {
        this.isOnDomains = false
        this.isOnSinformer = true
      }
      if (menu == 'domains') {
        this.isOnDomains = true
        this.isOnSinformer = false
      }
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
