<template>
  <div class="component--dropdown-user group">
    <el-dropdown
      ref="dropdown"
      class="w-full"
      :hide-on-click="false"
      trigger="click"
      @command="handleCommand"
    >
      <div
        class="el-dropdown-link flex justify-between items-center lg:max-w-[165px] xl:max-w-full"
      >
        <div class="flex pr-4 w-full">
          <Avatar
            v-if="$store.getters.user.profile"
            :source="
              $store.getters.user.profile.image
                ? $store.getters.user.profile.image.thumb
                : null
            "
            :fallback="$store.getters.user.profile.short_name"
            class="transition transform group-hover:scale-110"
          />

          <div
            v-if="$store.getters.isSidebarExpanded"
            class="flex flex-col ml-2 truncate"
          >
            <div class="text-[#111827] text-sm font-bold truncate">
              {{ $store.getters.user.profile.first_name }}
            </div>
            <div
              class="text-[#6B7280] text-sm truncate leading-tight font-normal"
            >
              {{
                $store.getters.contextStructure
                  ? $store.getters.contextStructure.name
                  : $store.getters.contextRoleLabel
              }}
            </div>
          </div>
        </div>
        <i
          class="el-icon-arrow-down el-icon--right"
          :class="[{ 'ml-1': !$store.getters.isSidebarExpanded }]"
        />
      </div>
      <el-dropdown-menu slot="dropdown" style="max-width: 300px">
        <nuxt-link to="/user/infos">
          <el-dropdown-item>Mon compte</el-dropdown-item>
        </nuxt-link>

        <el-dropdown-item
          v-for="structure in $store.getters.profile.structures"
          :key="'structure-' + structure.id"
          :command="{
            action: 'changeContext',
            context_role: 'responsable',
            contextable_type: 'structure',
            contextable_id: structure.id,
          }"
          divided
        >
          <div class="leading-normal py-2">
            <div class="uppercase font-medium text-gray-400 text-xs">
              Mon espace organisation
            </div>
            <div class="font-semibold">{{ structure.name }}</div>
          </div>
        </el-dropdown-item>

        <el-dropdown-item
          v-for="territoire in $store.getters.profile.territoires"
          :key="'territoire-' + territoire.id"
          :command="{
            action: 'changeContext',
            context_role: 'responsable',
            contextable_type: 'territoire',
            contextable_id: territoire.id,
          }"
          divided
        >
          <div class="leading-normal py-2">
            <div class="uppercase font-medium text-gray-400 text-xs">
              Mon espace
              {{ territoire.type | labelFromValue('territoires_types') }}
            </div>
            <div class="font-semibold">{{ territoire.name }}</div>
          </div>
        </el-dropdown-item>

        <el-dropdown-item
          v-for="role in $store.getters.roles.filter(
            (role) => role.key != 'responsable'
          )"
          :key="role.key"
          :command="{ action: 'changeContext', context_role: role.key }"
          divided
        >
          <div class="leading-normal py-2">
            <div class="uppercase font-medium text-gray-400 text-xs">
              Mon Espace {{ role.label }}
            </div>
            <div class="font-semibold">{{ role.label }}</div>
          </div>
        </el-dropdown-item>

        <el-dropdown-item v-if="isImpersonating" divided />

        <el-dropdown-item
          v-if="isImpersonating"
          class="!text-[#ed8936] !flex !space-between !items-center"
          :command="{ action: 'stopImpersonate' }"
        >
          Unmasquarade
          <i class="el-icon-s-custom ml-auto" />
        </el-dropdown-item>

        <el-dropdown-item
          divided
          :command="{ action: 'logout' }"
          class="!text-[#f56565]"
        >
          Se déconnecter
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
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
      if (command.action == 'changeContext') {
        await this.$store.dispatch('auth/updateUser', {
          context_role: command.context_role,
          contextable_type: command.contextable_type,
          contextable_id: command.contextable_id,
        })
        this.$refs.dropdown.visible = false

        let path = '/dashboard'
        if (command.context_role == 'responsable') {
          path = `/dashboard/${command.contextable_type}/${command.contextable_id}/statistics`
        }
        this.$router.push(path)

        if (this.$router.history.current.path == path) {
          this.$router.app.refresh()
        }
      }
    },
  },
}
</script>

<style lang="postcss" scoped>
.el-dropdown-link {
  cursor: pointer;
}
.el-icon-arrow-down {
  font-size: 12px;
}
.el-dropdown-menu {
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
