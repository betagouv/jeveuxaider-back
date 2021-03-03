<template>
  <el-aside
    :width="asideWidth"
    :class="{ collapsed: !$store.getters.isSidebarExpanded }"
    class="flex flex-col"
  >
    <DashboardDropdownUser class="border-b border-gray-200" />
    <div class="flex flex-col flex-1">
      <el-menu :router="true">
        <LazyDashboardMenuResponsable
          v-if="$store.getters.contextRole == 'responsable'"
        />
        <LazyDashboardMenuModerateur
          v-if="$store.getters.contextRole == 'admin'"
        />
        <LazyDashboardMenuReferent
          v-if="$store.getters.contextRole == 'referent'"
        />
        <LazyDashboardMenuReferentRegional
          v-if="$store.getters.contextRole == 'referent_regional'"
        />
        <LazyDashboardMenuSuperviseur
          v-if="$store.getters.contextRole == 'superviseur'"
        />
        <LazyDashboardMenuAnalyste
          v-if="$store.getters.contextRole == 'analyste'"
        />
      </el-menu>
    </div>

    <!-- <div class="text-center p-5">
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
      class="p-6 flex flex-col border-t border-gray-200 justify-center items-center"
    >
      <router-link to="/">
        <img alt="JeVeuxAider" src="/images/jeveuxaider-logo.svg" />
      </router-link>
      <div class="flex py-2 justify-center items-center">
        <router-link to="/dashboard/news">
          <div class="text-xs text-gray-600 hover:text-gray-800">
            Nouveautés
          </div>
        </router-link>
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
      class="p-2 flex flex-col border-t border-gray-200 justify-center items-center"
    >
      <router-link to="/dashboard/news" class="py-2">
        <el-tooltip
          class="item"
          :open-delay="500"
          effect="dark"
          content="Nouveautés"
          placement="right"
        >
          <i class="el-icon-news text-2xl text-gray-400" />
        </el-tooltip>
      </router-link>
      <a
        href="https://reserve-civique.crisp.help/fr/"
        target="_blank"
        class="py-2"
      >
        <el-tooltip
          class="item"
          :open-delay="500"
          effect="dark"
          content="Centre d'aide"
          placement="right"
        >
          <i class="el-icon-help text-2xl text-gray-400" />
        </el-tooltip>
      </a>
    </div> -->
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
      &:focus
        outline: none
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
