<template>
  <div>
    <el-dropdown @command="handleCommand">
      <el-avatar
        class="bg-white text-blue-900"
      >{{ $store.getters.user.profile.first_name[0] }}{{ $store.getters.user.profile.last_name[0] }}</el-avatar>
      <el-dropdown-menu slot="dropdown">
        <router-link v-if="$store.getters.contextRole == 'responsable'" to="/dashboard/missions">
          <el-dropdown-item class="flex items-center">
            <el-avatar
              class="bg-primary w-8 h-8 rounded-full mr-2 flex items-center justify-center border"
              :src="`${$store.getters.structure_as_responsable.logo}`"
            >{{ $store.getters.structure_as_responsable.name[0] }}</el-avatar>
            <v-clamp
              :max-lines="1"
              autoresize
              class="flex-1"
            >{{ $store.getters.structure_as_responsable.name }}</v-clamp>
          </el-dropdown-item>
        </router-link>
        <el-dropdown-item
          v-if="$store.getters.contextRole != 'responsable' &&
                $store.getters.contextRole != 'volontaire'
            "
          command="/dashboard"
        >Tableau de bord</el-dropdown-item>
        <el-dropdown-item v-if="$store.getters.contextRole != 'volontaire'" divided />
        <el-dropdown-item command="/user/profile">Profil</el-dropdown-item>
        <el-dropdown-item command="/user/settings">Paramètres de compte</el-dropdown-item>
        <el-dropdown-item divided command="/logout">Se déconnecter</el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </div>
</template>

<script>
export default {
  name: "DropdownUser",
  methods: {
    handleCommand(command) {
      this.$router.push(command);
    }
  }
};
</script>
