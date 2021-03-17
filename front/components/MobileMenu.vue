<template>
  <div>
    <div class="mr-4">
      <button
        v-show="!showMobileMenu"
        id="main-menu"
        type="button"
        class="text-blue-300"
        aria-haspopup="true"
        @click="showMobileMenu = true"
      >
        <span class="sr-only">Open main menu</span>
        <img
          class="h-5"
          src="/images/icones/menu.svg"
          alt="Open"
          width="20px"
          height="20px"
        />
      </button>
    </div>
    <div
      v-show="showMobileMenu"
      role="menu"
      aria-orientation="vertical"
      aria-labelledby="main-menu"
      class="fixed h-full w-full left-0 top-0 z-50 overflow-hidden"
    >
      <div class="bg-white h-full flex flex-col" role="none">
        <div
          class="flex items-center justify-between shadow"
          style="height: 80px"
        >
          <nuxt-link to="/" class="px-8" @click.native="closeMobileMenu">
            <img
              class=""
              src="@/assets/images/jeveuxaider-logo.svg"
              alt="JeVeuxAider.gouv.fr"
              width="227px"
              height="37px"
            />
          </nuxt-link>
          <button
            v-show="showMobileMenu"
            id="main-menu-close"
            type="button"
            class="text-blue-300 mr-4"
            aria-haspopup="true"
            @click="showMobileMenu = false"
          >
            <span class="sr-only">Close main menu</span>

            <img
              class="h-5"
              src="/images/icones/cancel.svg"
              alt="Close"
              width="20px"
              height="20px"
            />
          </button>
        </div>
        <div
          class="flex flex-col bg-gray-50 px-4 py-8 flex-1 overflow-y-scroll"
        >
          <button
            class="text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
            role="menuitem"
            @click="onMissionsClick"
          >
            Trouver une mission
          </button>
          <nuxt-link
            v-if="!$store.getters.isLogged"
            to="/register/responsable"
            class="px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
            role="menuitem"
            @click.native="closeMobileMenu"
            >Organisations</nuxt-link
          >
          <nuxt-link
            to="/territoires"
            class="px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
            role="menuitem"
            @click.native="closeMobileMenu"
            >Près de chez moi</nuxt-link
          >

          <a
            href="https://jeveuxaider.gouv.fr/engagement/actualites/"
            class="px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
            role="menuitem"
            >S'informer</a
          >
          <a
            target="_blank"
            rel="noopener"
            href="https://reserve-civique.crisp.help/fr/"
            class="px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
            role="menuitem"
            >Centre d'aide</a
          >
        </div>
        <div class="flex flex-col bg-blue-800 px-4 py-8">
          <template v-if="$store.getters.isLogged">
            <nuxt-link
              to="/user/infos"
              class="px-3 py-2 rounded-md text-base font-medium text-white"
              role="menuitem"
              @click.native="closeMobileMenu"
              >Mon compte</nuxt-link
            >
            <nuxt-link
              to="/user/missions"
              class="px-3 py-2 rounded-md text-base font-medium text-white"
              role="menuitem"
              @click.native="closeMobileMenu"
            >
              Mes missions
            </nuxt-link>
            <nuxt-link
              to="/messages"
              class="px-3 py-2 rounded-md text-base font-medium text-white"
              role="menuitem"
              @click.native="closeMobileMenu"
              >Mes messages</nuxt-link
            >
            <div
              class="px-3 py-2 rounded-md text-base font-medium text-white"
              role="menuitem"
              @click="handleLogout"
            >
              Se déconnecter
            </div>
          </template>
          <template v-else>
            <nuxt-link
              to="/login"
              class="px-3 py-2 rounded-md text-base font-medium text-white"
              role="menuitem"
              @click.native="closeMobileMenu"
              >Connexion</nuxt-link
            >
            <nuxt-link
              to="/register/volontaire"
              class="px-3 py-2 rounded-md text-base font-medium text-white"
              role="menuitem"
              @click.native="closeMobileMenu"
              >Inscription bénévole</nuxt-link
            >
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MobileMenu',
  data() {
    return {
      showMobileMenu: false,
    }
  },
  methods: {
    handleLogout() {
      console.log('handleLogout')
      this.closeMobileMenu()
      this.$store.dispatch('auth/logout')
    },
    closeMobileMenu() {
      this.showMobileMenu = false
    },
    onMissionsClick() {
      this.closeMobileMenu()
      this.$emit('mission-search-clicked')
    },
  },
}
</script>
