<template>
  <div>
    <el-dropdown
      ref="dropdown"
      class="w-full"
      :hide-on-click="false"
      trigger="click"
      @command="handleCommand"
    >
      <div class="el-dropdown-link flex justify-between p-6 items-center">
        <div class="flex">
          <el-avatar
            v-if="$store.getters.user.profile"
            :src="
              $store.getters.user.profile.image
                ? $store.getters.user.profile.image.thumb
                : null
            "
            class="bg-primary text-white"
          >
            {{ $store.getters.user.profile.short_name }}
          </el-avatar>
          <div
            v-if="$store.getters.isSidebarExpanded"
            class="flex flex-col ml-2"
          >
            <div class="text-black">
              {{ $store.getters.user.profile.first_name }}
            </div>
            <div class="uppercase text-xs">
              {{ $store.getters.contextRoleLabel }}
            </div>
          </div>
        </div>
        <i class="el-icon-arrow-down el-icon--right" />
      </div>
      <el-dropdown-menu slot="dropdown" style="max-width: 300px">
        <div v-if="activeMenu == 'profile'">
          <router-link
            v-if="$store.getters.contextRole == 'responsable'"
            :to="`/dashboard/structure/${$store.getters.structure_as_responsable.id}/members`"
          >
            <el-dropdown-item>Gérer votre équipe</el-dropdown-item>
          </router-link>
          <router-link
            v-if="$store.getters.contextRole == 'admin'"
            :to="`/dashboard/trash/structures`"
          >
            <el-dropdown-item>Corbeille</el-dropdown-item>
          </router-link>
          <el-dropdown-item
            v-if="
              $store.getters.contextRole == 'responsable' ||
              $store.getters.contextRole == 'admin'
            "
            divided
          />
          <router-link to="/user/infos">
            <el-dropdown-item>Mon compte</el-dropdown-item>
          </router-link>
          <el-dropdown-item
            v-if="$store.getters.hasRoles && $store.getters.hasRoles.length > 1"
            :command="{ action: 'menu', value: 'role' }"
            divided
          >
            <div class="flex space-between items-center">
              Changer de rôle
              <i class="el-icon-arrow-right ml-auto" />
            </div>
          </el-dropdown-item>
          <el-dropdown-item v-if="isImpersonating" divided />
          <el-dropdown-item
            v-if="isImpersonating"
            class="text-orange-500 flex space-between items-center"
            :command="{ action: 'stopImpersonate' }"
          >
            Unmasquarade
            <i class="el-icon-s-custom ml-auto" />
          </el-dropdown-item>
          <el-dropdown-item divided />
          <router-link to="/logout">
            <el-dropdown-item class="text-red-500">
              Se déconnecter
            </el-dropdown-item>
          </router-link>
        </div>
        <div v-if="activeMenu == 'role'">
          <el-dropdown-item :command="{ action: 'menu', value: 'profile' }">
            <i class="el-icon-arrow-left" />Retour
          </el-dropdown-item>
          <el-dropdown-item divided />
          <el-dropdown-item
            v-for="role in $store.getters.hasRoles"
            :key="role.key"
            :command="{ action: 'role', value: role.key }"
          >
            {{ role.label }}
          </el-dropdown-item>
        </div>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data() {
    return {
      activeMenu: 'profile',
    }
  },
  computed: {
    ...mapGetters(['isImpersonating']),
  },
  methods: {
    async handleCommand(command) {
      if (!command) {
        return
      }
      if (command.action == 'stopImpersonate') {
        await this.$store.dispatch('auth/stopImpersonate')
      }
      if (command.action == 'menu') {
        this.activeMenu = command.value
      } else if (command.action == 'role') {
        await this.$store.dispatch('user/setContextRole', command.value)
        window.location.href = '/dashboard'
      }
    },
  },
}
</script>

<style scoped>
.el-dropdown-link {
  cursor: pointer;
}
.el-icon-arrow-down {
  font-size: 12px;
}
.el-dropdown-menu {
  margin-top: -10px !important;
  margin-left: 16px !important;
}
.el-dropdown-menu__item {
  min-width: 230px;
}
.router-link-active li.el-dropdown-menu__item {
  @apply bg-gray-100;
  @apply text-primary;
}
</style>
