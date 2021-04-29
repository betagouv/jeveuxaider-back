<template>
  <div class="structure-view">
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
        :index="`/dashboard/collectivity/${collectivity.id}`"
      />
    </div>

    <div class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl font-semibold">Informations</div>
          </div>
          <ModelCollectivityInfos :collectivity="collectivity" />
        </el-card>
        <el-card v-if="collectivity.structure" shadow="never" class="p-4">
          <div class="flex justify-between">
            <nuxt-link
              :to="`/dashboard/structure/${collectivity.structure.id}`"
              class="mb-6 text-xl font-semibold hover:text-blue-800"
              >{{ collectivity.structure.name }}</nuxt-link
            >
          </div>
          <ModelStructureInfos :structure="collectivity.structure" />
        </el-card>
      </div>
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
