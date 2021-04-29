<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Collectivité</div>
        <div class="flex flex-wrap mb-8">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
            {{ collectivity.name }}
          </div>
          <TagModelState
            v-if="collectivity.state"
            :state="collectivity.state"
          />
          <el-tag v-if="collectivity.published" class="m-1 ml-0" size="medium">
            En ligne
          </el-tag>
          <el-tag v-if="!collectivity.published" class="m-1 ml-0" size="medium">
            Hors ligne
          </el-tag>
        </div>
      </div>
      <div>
        <DropdownCollectivityButton :collectivity="collectivity" />
      </div>
    </div>

    <div class="mb-12">
      <TabsCollectivity
        :collectivity="collectivity"
        :index="`/dashboard/collectivity/${collectivity.id}/missions`"
      />
    </div>

    <div class="px-12">
      <CardCollectivityMissionsCount
        label="Missions"
        name="missions"
        :collectivity="collectivity"
        :link="
          $store.getters.contextRole != 'responsable'
            ? `/dashboard/missions?filter[collectivity]=${collectivity.id}`
            : null
        "
      />
      <ChartCollectivityModelsCreated
        type="missions"
        :collectivity="collectivity"
        class="max-w-4xl"
      />
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      !['admin', 'referent', 'referent_regional', 'responsable'].includes(
        store.getters.contextRole
      )
    ) {
      return error({ statusCode: 403 })
    }
    if (store.getters.contextRole == 'responsable') {
      if (store.getters.structure.collectivity.id != params.id) {
        return error({ statusCode: 403 })
      }
    }
    const collectivity = await $api.getCollectivity(params.id)
    return {
      collectivity,
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
