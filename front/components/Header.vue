<template>
  <header class="bg-white relative z-20 shadow-lg">
    <JdmaBenevole
      v-if="
        $store.getters.isLogged &&
        $store.getters.user.nbParticipationsOver > 1 &&
        !$cookies.get('click-jdma')
      "
      @clicked="onClickJdma()"
    />

    <div
      id="header-wrapper"
      class="flex justify-between items-center px-4 lg:px-6"
      :class="[{ 'shadow-lg': showMobileMenu }]"
    >
      <nuxt-link to="/" class="flex items-center">
        <img
          class="flex-none ml-[-8px] lg:ml-[-11px] lg:mr-8 h-[70px] lg:h-[90px] w-auto"
          src="@/assets/images/republique-francaise-logo.svg"
          alt="République Française"
          width="166"
          height="150"
          data-not-lazy
        />

        <img
          src="@/assets/images/jeveuxaider-logo.svg"
          alt="Bénévolat je veux aider"
          title="Bénévolat association"
          width="251"
          height="41"
          class="jva-logo flex-none absolute inset-x-0 mx-auto lg:relative w-[180px] lg:w-auto"
          data-not-lazy
        />
      </nuxt-link>

      <!-- DESKTOP -->
      <div class="hidden lg:flex text-primary font-medium">
        <nav class="flex items-center text-sm -mr-4">
          <div class="flex items-center divide-x">
            <button
              id="search"
              class="flex items-center px-4 py-1 hover:underline group"
              @click="$store.commit('toggleSearchOverlay')"
            >
              <SearchSvg
                width="14"
                height="14"
                class="flex-none mr-2 transform transition group-hover:scale-125"
              />
              <span class="font-medium">Trouver une mission</span>
            </button>

            <nuxt-link
              :to="
                $store.getters.isLogged &&
                $store.getters.contextRole == 'responsable'
                  ? `/dashboard/structure/${$store.getters.contextStructure.id}/missions/add`
                  : '/inscription/organisation'
              "
              class="flex items-center px-4 py-1 hover:underline group"
            >
              <CalendarSvg
                width="15"
                height="15"
                class="flex-none mr-2 transform transition group-hover:scale-125"
              />
              <span>Publier une mission</span>
            </nuxt-link>

            <nuxt-link
              v-if="!$store.getters.isLogged"
              to="/register/volontaire"
              class="flex items-center px-4 py-1 hover:underline group"
            >
              <AvatarSvg
                class="flex-none mr-2 transform transition group-hover:scale-125"
              />
              <span>Devenir bénévole</span>
            </nuxt-link>
          </div>

          <div v-if="$store.getters.isLogged" class="flex items-center">
            <nuxt-link
              to="/messages"
              class="px-4 py-1 transform transition hover:scale-125"
            >
              <el-badge
                :value="$store.getters.user.unreadConversations.length"
                :hidden="$store.getters.user.unreadConversations.length == 0"
                is-dot
              >
                <EnvelopeSvg width="18" height="14" />
              </el-badge>
            </nuxt-link>

            <DropdownUser class="px-4 py-1" />
          </div>
        </nav>
      </div>

      <!-- MOBILE -->
      <div class="lg:hidden -m-2 flex">
        <button
          class="text-primary p-2"
          @click="$store.commit('toggleSearchOverlay')"
        >
          <SearchSvg width="20" height="20" class="flex-none" />
        </button>

        <button
          id="main-menu"
          type="button"
          class="text-primary p-2"
          aria-haspopup="true"
          @click="showMobileMenu = !showMobileMenu"
        >
          <template v-if="!showMobileMenu">
            <span class="sr-only">Open main menu</span>
            <BurgerMenu width="20" height="20" />
          </template>

          <template v-else>
            <span class="sr-only">Close main menu</span>
            <CloseSvg width="20" height="20" />
          </template>
        </button>
      </div>
    </div>

    <hr />

    <!-- DESKTOP - 2ND MENU-->
    <div class="hidden lg:block">
      <HeaderMenu />
    </div>

    <!-- MOBILE - MENU -->
    <transition name="fade">
      <MobileMenu v-if="showMobileMenu" />
    </transition>
  </header>
</template>

<script>
import SearchSvg from '@/static/images/search.svg?inline'
import CalendarSvg from '@/static/images/calendar2.svg?inline'
import AvatarSvg from '@/static/images/avatar.svg?inline'
import BurgerMenu from '@/static/images/icones/menu.svg?inline'
import CloseSvg from '@/static/images/icones/close.svg?inline'
import EnvelopeSvg from '@/static/images/icones/envelope.svg?inline'

export default {
  components: {
    SearchSvg,
    CalendarSvg,
    AvatarSvg,
    BurgerMenu,
    CloseSvg,
    EnvelopeSvg,
  },
  data() {
    return {
      showMobileMenu: false,
    }
  },
  watch: {
    $route() {
      this.showMobileMenu = false
    },
  },
  methods: {
    onClickJdma() {
      this.$cookies.set('click-jdma', true, {
        maxAge: 3600 * 24 * 365,
        path: '/',
        secure: true,
      })
    },
    goToDashboard() {
      if (this.$store.getters.contextRole == 'responsable') {
        this.$router.push(
          `/dashboard/${this.$store.getters.contextableType}/${this.$store.getters.contextStructure.id}/statistics`
        )
      } else {
        this.$router.push('/dashboard')
      }
    },
  },
}
</script>

<style lang="postcss" scoped>
#header-wrapper {
  height: 80px;
  @screen lg {
    height: 110px;
  }
}

.links-wrapper {
  @screen sm {
    transform: translateX(-0.5rem);
  }
  @screen lg {
    transform: inherit;
  }
}

.jva-logo {
  @media screen and (max-width: 320px) {
    width: 150px;
  }
}

.component--dropdown-user {
  ::v-deep {
    .el-dropdown-link {
      @apply xl:max-w-[200px];
    }

    .component--avatar {
      img {
        width: 34px;
        height: 34px;
        @apply rounded-full;
      }
    }
  }
}
</style>
