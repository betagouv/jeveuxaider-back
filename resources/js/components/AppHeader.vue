<template>
  <header class="bg-white relative z-20 shadow-lg">
    <jdma-benevole
      v-if="
        $store.getters.isAppLoaded &&
        $store.getters.isLogged &&
        $store.getters.participationsValidated > 1
      "
    />
    <div class="flex justify-between items-center" style="height: 110px">
      <div class="flex h-full">
        <div class="hidden md:flex items-center px-4 shadow-lg">
          <router-link to="/">
            <img
              class="mx-auto lg:mx-0"
              src="/images/republique-francaise-logo.png"
              alt="République Française"
              style="height: 82px"
            />
          </router-link>
        </div>
        <div class="flex items-center px-8">
          <router-link to="/">
            <img
              class=""
              src="/images/jeveuxaider-logo.svg"
              alt="JeVeuxAider.gouv.fr"
            />
          </router-link>
        </div>
      </div>

      <!-- DESKTOP -->
      <div class="hidden md:flex md:flex-col h-full">
        <div class="flex justify-end">
          <slot name="top-menu">
            <router-link
              v-if="
                $store.getters.isLogged &&
                $store.getters.contextRole != 'volontaire'
              "
              to="/dashboard"
              class="uppercase bg-gray-50 text-xxs text-gray-400 hover:text-blue-800 px-12 py-2 transition ease-in-out duration-150"
            >
              Tableau de bord
            </router-link>
            <router-link
              v-if="!$store.getters.isLogged"
              to="/register/responsable"
              class="uppercase bg-gray-50 text-xxs text-gray-400 hover:text-blue-800 px-12 py-2 transition ease-in-out duration-150"
            >
              Publier mes missions
            </router-link>
            <router-link
              v-if="!$store.getters.isLogged"
              to="/register/responsable"
              class="ml-1 uppercase bg-gray-50 text-xxs text-gray-400 hover:text-blue-800 px-12 py-2 transition ease-in-out duration-150"
            >
              Inscrire ma collectivité
            </router-link>
            <a
              target="_blank"
              href="https://reserve-civique.crisp.help/fr/"
              class="ml-1 uppercase bg-gray-50 text-xxs text-gray-400 hover:text-blue-800 px-12 py-2 transition ease-in-out duration-150"
            >
              Foire aux questions
            </a>
            <div
              class="flex justify-center items-center ml-1 bg-gray-50 text-xxs text-gray-400 hover:text-blue-800 px-4 py-2 transition ease-in-out duration-150"
            >
              <a
                target="_blank"
                href="https://www.facebook.com/reservecivique/"
                class="px-2"
                ><img src="/images/icones/facebook.svg" alt="Facebook"
              /></a>
              <a
                target="_blank"
                href="https://twitter.com/reservecivique"
                class="px-2"
                ><img src="/images/icones/twitter.svg" alt="Twitter"
              /></a>
              <a target="_blank" href="#" class="px-2"
                ><img src="/images/icones/instagram.svg" alt="Instagram"
              /></a>
              <a
                target="_blank"
                href="https://www.linkedin.com/company/reservecivique/"
                class="px-2"
                ><img src="/images/icones/linkedin.svg" alt="Linkedin"
              /></a>
            </div>
          </slot>
        </div>
        <div class="flex h-full items-center justify-end px-4">
          <slot name="menu">
            <nav class="flex space-x-4 text-sm lg:text-base lg:space-x-6">
              <router-link
                to="/missions"
                class="flex leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
              >
                <img
                  class="mr-2"
                  src="/images/icones/search.svg"
                  alt="Trouver une mission"
                />
                Trouver une mission
              </router-link>
              <router-link
                to="/territoires"
                class="leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
              >
                Près de chez moi
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
                    class="absolute transform mt-3 px-2 w-screen max-w-xs sm:px-0"
                    style="left: -100px"
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
              <router-link
                v-if="$store.getters.isLogged"
                to="/user/missions"
                class="leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
              >
                Mes missions
              </router-link>
            </nav>
          </slot>
          <div class="ml-12">
            <template v-if="!$store.getters.isLogged">
              <router-link
                to="/login"
                class="flex border border-gray-200 cursor-pointer rounded-full px-4 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-50 hover:text-blue-800 focus:text-gray-900 transition ease-in-out duration-150"
              >
                <img
                  class="mr-2"
                  src="/images/icones/mon-espace.svg"
                  alt="Mon espace"
                />
                Mon espace
              </router-link>
            </template>
            <template v-else>
              <div class="flex items-center space-x-4">
                <router-link
                  v-if="
                    $store.getters.isLogged &&
                    $store.getters.reminders &&
                    $store.getters.contextRole != 'volontaire' &&
                    $store.getters.reminders.total > 0
                  "
                  to="/dashboard"
                >
                  <el-badge
                    :value="$store.getters.reminders.total"
                    is-dot
                    :max="99"
                  >
                    <svg
                      class="h-6 w-6 text-blue-300 hover:text-blue-800 transition ease-in-out duration-150"
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
                <router-link v-if="$store.getters.isLogged" to="/messages">
                  <el-badge
                    :value="$store.getters.user.nbUnreadConversations"
                    :hidden="!$store.getters.user.nbUnreadConversations"
                    is-dot
                  >
                    <svg
                      class="h-6 w-6 text-blue-300 hover:text-blue-800 transition ease-in-out duration-150"
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
                <dropdown-front-user></dropdown-front-user>
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- MOBILE -->
      <mobile-menu
        class="flex h-full items-center md:hidden"
        @menu-closed="handleMenuClosed"
      />
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
      isOnSinformer: false,
      //showMobileMenu: false,
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
    handleMenuClosed() {
      // this.showMobileMenu = false
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
