<template>
  <el-aside
    :width="asideWidth"
    :class="{ collapsed: !$store.getters.sidebar }"
    class="flex flex-col"
  >
    <profile class="border-b border-gray-200" />
    <div class="flex flex-col flex-1">
      <el-menu :router="true">
        <menu-responsable v-if="$store.getters.contextRole == 'responsable'" />
        <menu-moderateur v-if="$store.getters.contextRole == 'admin'" />
        <menu-referent v-if="$store.getters.contextRole == 'referent'" />
        <menu-referent-regional
          v-if="$store.getters.contextRole == 'referent_regional'"
        />
        <menu-superviseur v-if="$store.getters.contextRole == 'superviseur'" />
        <menu-analyste v-if="$store.getters.contextRole == 'analyste'" />
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
      class="p-6 flex flex-col border-t border-gray-200 justify-center items-center"
    >
      <router-link to="/">
        <img
          v-if="$store.getters.sidebar"
          src="/images/logo-header-dark-small.png"
        />
        <img v-else src="/images/logo-rc-square.png" />
      </router-link>
      <div class="hidden md:flex md:justify-center md:items-center md:py-2">
        <router-link v-if="$store.getters.sidebar" to="/dashboard/news">
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
  </el-aside>
</template>

<script>
import MenuResponsable from './MenuResponsable'
import MenuModerateur from './MenuModerateur'
import MenuReferent from './MenuReferent'
import MenuReferentRegional from './MenuReferentRegional'
import MenuSuperviseur from './MenuSuperviseur'
import MenuAnalyste from './MenuAnalyste'
import Profile from './Profile'

export default {
  name: 'Sidebar',
  components: {
    MenuResponsable,
    MenuModerateur,
    MenuReferent,
    MenuReferentRegional,
    MenuSuperviseur,
    MenuAnalyste,
    Profile,
  },
  computed: {
    asideWidth() {
      return this.$store.getters.sidebar ? '232px' : '88px'
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
