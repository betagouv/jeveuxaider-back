<template>
  <div class="flex flex-shrink-0">
    <div
      class="
        flex flex-col
        overflow-x-hidden overflow-y-auto
        border-gray-200 border-r
        w-64
        pt-5
      "
    >
      <div
        v-if="$store.getters.isSidebarExpanded"
        class="px-6 flex flex-col justify-center items-center"
      >
        <nuxt-link to="/">
          <img
            alt="JeVeuxAider"
            class="h-8 w-auto"
            src="@/assets/images/jeveuxaider-logo.svg"
          />
        </nuxt-link>
      </div>

      <div class="px-3 mt-6 relative inline-block text-left">
        <DropdownUser class="px-3.5 py-2 hover:bg-gray-200 rounded-md" />
      </div>

      <nav class="px-3 mt-6">
        <LazyMenuResponsable
          v-if="
            $store.getters.contextRole == 'responsable' &&
            $store.getters.contextableType == 'structure'
          "
        />
        <LazyMenuResponsableTerritoire
          v-if="
            $store.getters.contextRole == 'responsable' &&
            $store.getters.contextableType == 'territoire'
          "
        />
        <LazyMenuModerateur v-if="$store.getters.contextRole == 'admin'" />
        <LazyMenuReferent v-if="$store.getters.contextRole == 'referent'" />
        <LazyMenuReferentRegional
          v-if="$store.getters.contextRole == 'referent_regional'"
        />
        <LazyMenuSuperviseur
          v-if="$store.getters.contextRole == 'superviseur'"
        />
        <LazyMenuAnalyste v-if="$store.getters.contextRole == 'analyste'" />

        <!-- Secondary navigation -->
        <div class="mt-8">
          <h3
            id="teams-headline"
            class="
              px-3
              text-xs
              font-semibold
              text-gray-500
              uppercase
              tracking-wider
            "
          >
            Liens utiles
          </h3>
          <div
            class="mt-1 space-y-1"
            role="group"
            aria-labelledby="teams-headline"
          >
            <nuxt-link
              v-if="
                ['referent', 'responsable', 'admin'].includes(
                  $store.getters.contextRole
                )
              "
              to="/dashboard/ressources"
              class="
                group
                flex
                items-center
                px-3
                py-2
                text-sm
                font-medium
                text-gray-700
                rounded-md
                hover:text-gray-900
                hover:bg-gray-50
              "
              :class="{
                'bg-gray-50': doesPathContains('dashboard/ressources'),
              }"
            >
              <span
                class="w-2.5 h-2.5 mr-4 bg-purple-500 rounded-full"
                aria-hidden="true"
              ></span>
              <span class="truncate"> Ressources </span>
            </nuxt-link>

            <nuxt-link
              to="/dashboard/news"
              class="
                group
                flex
                items-center
                px-3
                py-2
                text-sm
                font-medium
                text-gray-700
                rounded-md
                hover:text-gray-900
                hover:bg-gray-50
              "
              :class="{ 'bg-gray-50': doesPathContains('dashboard/news') }"
            >
              <span
                class="w-2.5 h-2.5 mr-4 bg-green-500 rounded-full"
                aria-hidden="true"
              ></span>
              <span class="truncate"> Nouveautés </span>
            </nuxt-link>

            <a
              href="https://reserve-civique.crisp.help/fr/"
              target="_blank"
              class="
                group
                flex
                items-center
                px-3
                py-2
                text-sm
                font-medium
                text-gray-700
                rounded-md
                hover:text-gray-900
                hover:bg-gray-50
              "
            >
              <span
                class="w-2.5 h-2.5 mr-4 bg-indigo-500 rounded-full"
                aria-hidden="true"
              ></span>
              <span class="truncate"> Centre d'aide </span>
            </a>

            <a
              href="https://go.crisp.chat/chat/embed/?website_id=4b843a95-8a0b-4274-bfd5-e81cbdc188ac"
              target="_blank"
              class="
                group
                flex
                items-center
                px-3
                py-2
                text-sm
                font-medium
                text-gray-700
                rounded-md
                hover:text-gray-900
                hover:bg-gray-50
              "
            >
              <span
                class="w-2.5 h-2.5 mr-4 bg-red-500 rounded-full"
                aria-hidden="true"
              ></span>
              <span class="truncate"> Contacter le support </span>
            </a>
          </div>
        </div>
      </nav>
    </div>
  </div>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
  computed: {
    asideWidth() {
      return this.$store.getters.isSidebarExpanded ? '256px' : '88px'
    },
  },
  methods: {
    handleCollapse() {
      this.$store.commit('toggleSidebar')
    },
  },
}
</script>

<style scoped lang="sass">
.el-menu
  background-color: transparent
  border: 0
  ::v-deep .el-menu-item
    i
      font-size: 24px
      margin: 0
    &.is-active
      &::before
        content: ""
        position: absolute
        top: 0
        left: 0
        width: 4px
        height: 100%
        @apply bg-primary

// .el-aside
//   transition: width .25s
.toggle-collapse
  ::v-deep i
    transition: transform .25s
.collapsed
  ::v-deep .el-menu-item
    text-align: center
  .toggle-collapse
    ::v-deep i
      transform: rotate(180deg)
</style>
