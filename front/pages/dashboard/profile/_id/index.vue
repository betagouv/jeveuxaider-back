<template>
  <div class="">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Utilisateur</div>
        <div class="mb-8 flex">
          <div class="font-bold text-2xl text-gray-800">
            {{ profile.first_name }} {{ profile.last_name }}
          </div>
          <TagProfileRoles
            :profile="profile"
            size="medium"
            class="flex items-center ml-3"
          />
        </div>
      </div>
      <div>
        <DropdownProfileButton :profile="profile" />
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/profile/${profile.id}`">
        Informations
      </el-menu-item>
      <el-menu-item :index="`/dashboard/profile/${profile.id}/activity`">
        Activités
      </el-menu-item>
      <el-menu-item :index="`/dashboard/profile/${profile.id}/history`">
        Historique
      </el-menu-item>
    </el-menu>

    <div class="px-12 grid grid-cols-1 gap-4 xl:grid-cols-2">
      <el-card shadow="never" class="p-4">
        <div class="flex justify-between">
          <div class="mb-6 text-xl">Informations</div>
        </div>
        <ModelProfileInfos :profile="profile" />
      </el-card>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      !['admin', 'referent', 'referent_regional'].includes(
        store.getters.contextRole
      )
    ) {
      return error({ statusCode: 403 })
    }
    const profile = await $api.getProfile(params.id)
    return {
      profile,
    }
  },
  methods: {},
}
</script>

<style scoped lang="sass">
.el-menu--horizontal
  @apply px-12
  > .el-menu-item
    border-bottom: solid 3px #070191
    @apply mr-8 p-0 font-medium
</style>
