<template>
  <div>
    <el-dropdown @command="handleCommand">
      <el-avatar>NV</el-avatar>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item command="/user/profile">Profil</el-dropdown-item>
        <el-dropdown-item command="/user/settings">Paramètres de compte</el-dropdown-item>
        <el-dropdown-item divided command="/user/logout">Se déconnecter</el-dropdown-item>
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
