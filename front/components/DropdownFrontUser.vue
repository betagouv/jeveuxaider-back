<template>
  <div class="m-2">
    <el-dropdown v-if="$store.getters.user.profile" @command="handleCommand">
      <div
        class="el-dropdown-link flex border border-gray-200 cursor-pointer rounded-full px-4 py-2 text-xs font-semibold text-[#242526] hover:bg-gray-50 hover:text-[#070191] focus:text-gray-900 transition ease-in-out duration-150"
      >
        <img
          class="mr-2"
          src="@/assets/images/icones/mon-espace.svg"
          alt="Mon espace"
          width="12"
          height="18"
        />
        {{ $store.getters.user.profile.first_name }}
      </div>
      <el-dropdown-menu slot="dropdown">
        <!-- <el-dropdown-item
          v-if="$store.getters.contextRole == 'responsable'"
          command="/dashboard"
          class="flex items-center"
        >
          <Avatar
            v-if="$store.getters.structure && $store.getters.structure.name"
            :source="
              $store.getters.structure.logo
                ? $store.getters.structure.logo.thumb
                : null
            "
            :fallback="$store.getters.structure.name[0]"
            class="mr-2"
            width="w-6 h-6"
            font-size="text-xs"
          />

          <span class="truncate">{{ $store.getters.structure.name }}</span>
        </el-dropdown-item> -->

        <template
          v-if="
            $store.getters.contextRole &&
            !['volontaire'].includes($store.getters.contextRole)
          "
        >
          <el-dropdown-item command="dashboard">
            Tableau de bord
          </el-dropdown-item>

          <el-dropdown-item divided />
        </template>
        <el-dropdown-item command="/user/infos"> Mon compte </el-dropdown-item>
        <el-dropdown-item command="/user/missions">
          Mes missions
        </el-dropdown-item>
        <el-dropdown-item
          v-if="$store.getters.isImpersonating"
          divided
          class="text-orange-500"
          :command="{ action: 'stopImpersonate' }"
        >
          Unmasquarade
        </el-dropdown-item>
        <el-dropdown-item divided :command="{ action: 'logout' }">
          Se déconnecter
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
</template>

<script>
export default {
  methods: {
    async handleCommand(command) {
      if (command.action == 'stopImpersonate') {
        await this.$store.dispatch('auth/stopImpersonate')
      } else if (command.action == 'logout') {
        this.$router.push('/')
        await this.$store.dispatch('auth/logout')
      } else if (command == 'dashboard') {
        if (this.$store.getters.contextRole == 'responsable') {
          this.$router.push(
            `/dashboard/${this.$store.getters.contextableType}/${this.$store.getters.contextStructure.id}/statistics`
          )
        } else {
          this.$router.push('/dashboard')
        }
      } else {
        this.$router.push(command)
      }
    },
  },
}
</script>

<style lang="postcss" scoped>
.el-dropdown-menu {
  width: 200px;
}
</style>
