<template>
  <div class="">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Participation</div>
        <div
          v-if="participation.profile"
          class="flex items-center flex-wrap mb-8"
        >
          <div class="font-bold text-2-5xl text-[#242526] mr-2">
            {{ participation.profile.full_name }}
          </div>
          <TagModelState :state="participation.state" />
        </div>
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/participation/${participation.id}`">
        Informations
      </el-menu-item>
      <el-menu-item
        :index="`/dashboard/participation/${participation.id}/history`"
      >
        Historique
      </el-menu-item>
    </el-menu>

    <div class="px-12 grid grid-cols-1 gap-4 xl:grid-cols-2">
      <el-card shadow="never" class="p-4">
        <div class="flex justify-between">
          <div class="mb-6 text-xl font-semibold">Informations</div>
        </div>
        <ModelParticipationInfos :participation="participation" />
      </el-card>
      <el-card v-if="mission" shadow="never" class="p-4">
        <div class="flex justify-between">
          <div class="mb-6 text-xl font-semibold">Mission</div>
        </div>
        <ModelMissionInfos :mission="mission" />
      </el-card>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      ![
        'admin',
        'referent',
        'referent_regional',
        'superviseur',
        'responsable',
      ].includes(store.getters.contextRole)
    ) {
      return error({ statusCode: 403 })
    }
    const participation = await $api.getParticipation(params.id)
    const mission = await $api.getMission(participation.mission_id)
    return {
      participation,
      mission,
    }
  },
}
</script>

<style scoped lang="postcss">
.el-menu--horizontal {
  @apply px-12;
  > .el-menu-item {
    @apply mr-8 p-0 font-medium;
    border-bottom: solid 3px #070191;
  }
}
</style>
