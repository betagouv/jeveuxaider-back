<template>
  <div class="layout-default h-full">
    <el-container class="h-full" v-if="isAppLoaded">
      <el-header class="flex justify-between items-center">
        <div class="text-2xl text-white">Réserve Civique</div>
        <div class="">
          <el-dropdown>
            <el-avatar class="bg-gray-100"><span class="text-gray-700">{{ $store.getters['user/shortName'] }}</span></el-avatar>
            <el-dropdown-menu slot="dropdown">
              <router-link :to="{ name: 'settings'}"><el-dropdown-item>Mes paramètres</el-dropdown-item></router-link>
              <el-dropdown-item><span @click="handleClickLogout">Se déconnecter</span></el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </div>
      </el-header>
      <el-container class="h-full">
        <el-aside width="250px" class="h-full">
          <el-menu :router="true">
            <el-menu-item index="1" :route="{ name: 'dashboard' }">
              <font-awesome-icon class="mr-5 fa-fw" icon="th" />
              <span>Tableau de bord</span>
            </el-menu-item>
            <el-menu-item index="2" :route="{ name: 'activities' }">
              <font-awesome-icon class="mr-5 fa-fw" icon="folder" />
              <span>Activités</span>
            </el-menu-item>
            <el-menu-item index="3" :route="{ name: 'collaborators' }">
              <font-awesome-icon class="mr-5 fa-fw" icon="user-friends" />
              <span>Collaborateurs</span>
            </el-menu-item>
          </el-menu>
        </el-aside>
        <el-main class="p-12">
          <transition name="fade" mode="out-in">
            <router-view />
          </transition>
        </el-main>
      </el-container>
    </el-container>
    <div class="h-full flex justify-center items-center" v-else>
      <font-awesome-icon icon="spinner" class="fa-spin"/>
    </div>
  </div>
</template>

<script type="text/babel">
import { mapGetters } from 'vuex'

export default {
  data() {
    return {
      user: {},
    };
  },
  computed: {
    ...mapGetters([
      'isAppLoaded'
    ]),
  },
  created() {
    this.$store.dispatch('bootstrap')
  },
  methods: {
    handleClickLogout() {
      this.$store.dispatch("auth/logout")
    }
  }
};
</script>
