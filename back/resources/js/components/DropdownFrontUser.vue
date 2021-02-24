<template>
  <div class="m-2">
    <el-dropdown v-if="$store.getters.user.profile" @command="handleCommand">
      <div
        class="el-dropdown-link flex border border-gray-200 cursor-pointer rounded-full px-4 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-50 hover:text-blue-800 focus:text-gray-900 transition ease-in-out duration-150"
      >
        <img
          class="mr-2"
          src="/images/icones/mon-espace.svg"
          alt="Mon espace"
        />
        {{ $store.getters.user.profile.first_name }}
      </div>
      <el-dropdown-menu slot="dropdown">
        <router-link
          v-if="$store.getters.contextRole == 'responsable'"
          to="/dashboard"
        >
          <el-dropdown-item class="flex items-center">
            <el-avatar
              v-if="
                $store.getters.structure_as_responsable &&
                $store.getters.structure_as_responsable.name
              "
              class="bg-primary w-8 h-8 rounded-full mr-2 flex items-center justify-center border"
              :src="`${$store.getters.structure_as_responsable.logo}`"
            >
              {{ $store.getters.structure_as_responsable.name[0] }}
            </el-avatar>
            <v-clamp :max-lines="1" autoresize class="flex-1">
              {{ $store.getters.structure_as_responsable.name }}
            </v-clamp>
          </el-dropdown-item>
        </router-link>
        <template
          v-if="
            $store.getters.contextRole &&
            !['volontaire', 'responsable'].includes($store.getters.contextRole)
          "
        >
          <el-dropdown-item command="/dashboard">
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
        <el-dropdown-item divided command="/logout">
          Se déconnecter
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
</template>

<script>
export default {
  name: 'DropdownFrontUser',
  methods: {
    async handleCommand(command) {
      if (command.action == 'stopImpersonate') {
        await this.$store.dispatch('auth/stopImpersonate')
      } else {
        this.$router.push(command)
      }
    },
  },
}
</script>

<style lang="sass" scoped>
.el-dropdown-menu
    width: 200px
</style>
