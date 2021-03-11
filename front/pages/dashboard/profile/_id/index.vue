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

    <!-- <template v-else-if="tab == 'activities'">
      <TableActivities :table-data="tableData" />
    </template>
    <template v-else-if="tab == 'history'">
      <TableActivities :table-data="tableData" />
    </template>
    <div v-if="tab" class="m-3 flex items-center">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="totalRows"
        :page-size="15"
        :current-page="Number(query.page)"
        @current-change="onPageChange"
      />
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div> -->
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const profile = await $api.getProfile(params.id)
    return {
      profile,
    }
  },
  methods: {
    // async fetchRows() {
    //   const response = await getProfile(this.id)
    //   this.profile = response.data
    //   if (this.tab == 'history') {
    //     return fetchActivities({
    //       'filter[subject_id]': this.id,
    //       'filter[subject_type]': 'Profile',
    //       page: this.$route.query.page || 1,
    //     })
    //   }
    //   if (this.tab == 'activities' && this.profile.user_id) {
    //     return fetchActivities({
    //       'filter[causer_id]': this.profile.user_id,
    //       'filter[causer_type]': 'User',
    //       page: this.$route.query.page || 1,
    //     })
    //   }
    // },
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
