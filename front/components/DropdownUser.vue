<template>
  <div>
    <el-dropdown
      ref="dropdown"
      class="w-full"
      :hide-on-click="false"
      trigger="click"
      @command="handleCommand"
    >
      <div class="el-dropdown-link flex justify-between p-5 items-center">
        <div class="flex">
          <Avatar
            v-if="$store.getters.user.profile"
            :source="
              $store.getters.user.profile.image
                ? $store.getters.user.profile.image.thumb
                : null
            "
            :fallback="$store.getters.user.profile.short_name"
          />

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
        <i
          class="el-icon-arrow-down el-icon--right"
          :class="[{ 'ml-1': !$store.getters.isSidebarExpanded }]"
        />
      </div>
      <el-dropdown-menu slot="dropdown" style="max-width: 300px">
        <div v-if="activeMenu == 'profile'">
          <nuxt-link
            v-if="$store.getters.contextRole == 'responsable'"
            :to="`/dashboard/structure/${$store.getters.structure.id}/members`"
          >
            <el-dropdown-item>Gérer votre équipe</el-dropdown-item>
          </nuxt-link>
          <nuxt-link
            v-if="$store.getters.contextRole == 'admin'"
            :to="`/dashboard/trash/structures`"
          >
            <el-dropdown-item>Corbeille</el-dropdown-item>
          </nuxt-link>
          <el-dropdown-item
            v-if="
              $store.getters.contextRole == 'responsable' ||
              $store.getters.contextRole == 'admin'
            "
            divided
          />
          <nuxt-link to="/user/infos">
            <el-dropdown-item>Mon compte</el-dropdown-item>
          </nuxt-link>
          <el-dropdown-item
            v-if="$store.getters.roles && $store.getters.roles.length > 1"
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
          <el-dropdown-item
            divided
            :command="{ action: 'logout' }"
            class="text-red-500"
          >
            Se déconnecter
          </el-dropdown-item>
        </div>
        <div v-if="activeMenu == 'role'">
          <el-dropdown-item :command="{ action: 'menu', value: 'profile' }">
            <i class="el-icon-arrow-left" />Retour
          </el-dropdown-item>
          <el-dropdown-item divided />
          <el-dropdown-item
            v-for="role in $store.getters.roles"
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
      } else if (command.action == 'logout') {
        this.$router.push('/')
        await this.$store.dispatch('auth/logout')
      }
      if (command.action == 'menu') {
        this.activeMenu = command.value
      } else if (command.action == 'role') {
        await this.$store.dispatch('auth/updateUser', {
          context_role: command.value,
        })
        this.$refs.dropdown.visible = false
        // TODO: pb de cache en prod, il faut reload la page
        this.$message({
          message: 'Vous allez être transféré dans quelques secondes !',
          type: 'success',
        })
        setTimeout(() => {
          window.location.href = '/dashboard'
        }, 1000)
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
.nuxt-link-active li.el-dropdown-menu__item {
  @apply bg-gray-100;
  @apply text-primary;
}
</style>
