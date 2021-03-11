<template>
  <el-dropdown split-button type="primary" @command="handleCommand">
    <nuxt-link :to="`/dashboard/profile/${profile.id}/edit`">
      Modifier l'utilisateur
    </nuxt-link>
    <el-dropdown-menu slot="dropdown">
      <el-dropdown-item
        v-if="profile.user_id"
        :command="{ action: 'impersonate', id: profile.user_id }"
      >
        Prendre sa place
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  props: {
    profile: {
      type: Object,
      required: true,
    },
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'impersonate') {
        this.$store.dispatch('auth/impersonate', command.id)
      }
    },
  },
}
</script>

<style scoped lang="sass">
.el-menu--horizontal
  @apply px-12
  > .el-menu-item
    @apply mr-8 p-0 font-medium
    border-bottom: solid 3px #070191
</style>
