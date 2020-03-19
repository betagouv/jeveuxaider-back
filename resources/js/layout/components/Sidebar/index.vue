<template>
  <el-aside :width="asideWidth" :class="{ collapsed: isCollapsed }">
    <profile :is-collapsed="isCollapsed" />

    <div class="px-5">
      <hr />
    </div>

    <el-menu :router="true">
      <menu-responsable
        v-if="$store.getters.contextRole == 'responsable'"
        :default-active="activeMenu"
        :is-collapsed="isCollapsed"
      />
      <menu-moderateur
        v-if="$store.getters.contextRole == 'admin'"
        :default-active="activeMenu"
        :is-collapsed="isCollapsed"
      />
      <menu-referent
        v-if="$store.getters.contextRole == 'referent'"
        :default-active="activeMenu"
        :is-collapsed="isCollapsed"
      />
      <menu-superviseur
        v-if="$store.getters.contextRole == 'superviseur'"
        :default-active="activeMenu"
        :is-collapsed="isCollapsed"
      />
      <menu-tuteur
        v-if="$store.getters.contextRole == 'tuteur'"
        :default-active="activeMenu"
        :is-collapsed="isCollapsed"
      />
    </el-menu>

    <div class="px-5">
      <hr />
    </div>

    <div class="text-center p-5">
      <el-button
        icon="el-icon-arrow-left"
        size="small"
        circle
        plain
        class="toggle-collapse"
        @click="handleCollapse"
      ></el-button>
    </div>
    <router-link to="/">
        <div v-if="!isCollapsed" class="absolute bottom-0 p-6 pb-10 flex justify-center mb-4" style="width: 220px;">
        <img
            src="/images/logo-reserve-civique_dark.svg"
        />
        </div>
    </router-link>
  </el-aside>
</template>

<script>
import MenuResponsable from "./MenuResponsable";
import MenuModerateur from "./MenuModerateur";
import MenuReferent from "./MenuReferent";
import MenuSuperviseur from "./MenuSuperviseur";
import MenuTuteur from "./MenuTuteur";
import Profile from "./Profile";
import { mapGetters } from "vuex";

export default {
  name: "Sidebar",
  components: {
    MenuResponsable,
    MenuModerateur,
    MenuReferent,
    MenuSuperviseur,
    MenuTuteur,
    Profile
  },
  data() {
    return {
      isCollapsed: false
    };
  },
  computed: {
    ...mapGetters(["release"]),
    activeMenu() {
      return this.$route.path;
    },
    asideWidth() {
      return this.isCollapsed ? "88px" : "232px";
    }
  },
  methods: {
    handleCollapse() {
      this.isCollapsed = !this.isCollapsed;
    }
  }
};
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
