<template>
  <div>
    <el-dropdown @command="handleCommand">
      <el-avatar>NV</el-avatar>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item command="/user/profile">Profil</el-dropdown-item>
        <el-dropdown-item command="/user/settings"
          >Paramètres de compte</el-dropdown-item
        >
        <el-dropdown-item divided command="/user/logout"
          >Se déconnecter</el-dropdown-item
        >
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
