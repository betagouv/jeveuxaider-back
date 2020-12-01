<template>
  <div class="m-2">
    <el-dropdown @command="handleCommand">
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
        <el-dropdown-item
          v-if="
            $store.getters.contextRole != 'responsable' &&
            $store.getters.contextRole != 'volontaire'
          "
          command="/dashboard"
        >
          Tableau de bord
        </el-dropdown-item>
        <el-dropdown-item
          v-if="$store.getters.contextRole != 'volontaire'"
          divided
        />
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
  name: 'DropdownUser',
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
