<template>
  <el-aside
    :width="asideWidth"
    :class="{ collapsed: !$store.getters.isSidebarExpanded }"
    class="flex flex-col overflow-x-hidden overflow-y-auto"
  >
    <DropdownUser class="border-b border-gray-200" />

    <div class="flex flex-col flex-1">
      <el-menu :router="true">
        <LazyMenuResponsable
          v-if="$store.getters.contextRole == 'responsable'"
        />
        <LazyMenuResponsableTerritoire
          v-if="$store.getters.contextRole == 'responsable_territoire'"
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
      </el-menu>
    </div>

    <div class="text-center p-5">
      <el-button
        icon="el-icon-arrow-left"
        size="small"
        circle
        plain
        class="toggle-collapse"
        @click="handleCollapse"
      />
    </div>

    <div
      v-if="$store.getters.isSidebarExpanded"
      class="
        p-5
        flex flex-col
        border-t border-gray-200
        justify-center
        items-center
      "
    >
      <nuxt-link to="/">
        <img alt="JeVeuxAider" src="@/assets/images/jeveuxaider-logo.svg" />
      </nuxt-link>
      <div class="flex py-2 justify-center items-center">
        <nuxt-link to="/dashboard/news">
          <div class="text-xs text-gray-600 hover:text-gray-800">
            Nouveautés
          </div>
        </nuxt-link>
        <span class="mx-2 text-gray-700">-</span>
        <a
          class="text-xs text-gray-600 hover:text-gray-800"
          href="https://reserve-civique.crisp.help/fr/"
          target="_blank"
        >
          Centre d'aide
        </a>
      </div>
    </div>
    <div
      v-else
      class="
        p-2
        flex flex-col
        border-t border-gray-200
        justify-center
        items-center
      "
    >
      <nuxt-link
        v-tooltip.right="{
          content: 'Nouveautés',
          classes: 'bo-style',
        }"
        to="/dashboard/news"
        class="py-2"
      >
        <i class="el-icon-news text-2xl text-gray-400" />
      </nuxt-link>
      <a
        v-tooltip.right="{
          content: `Centre d'aide`,
          classes: 'bo-style',
        }"
        href="https://reserve-civique.crisp.help/fr/"
        target="_blank"
        class="py-2"
      >
        <i class="el-icon-help text-2xl text-gray-400" />
      </a>
    </div>
  </el-aside>
</template>

<script>
export default {
  computed: {
    asideWidth() {
      return this.$store.getters.isSidebarExpanded ? '232px' : '88px'
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
