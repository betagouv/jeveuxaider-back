<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Collectivité</div>
        <div class="flex flex-wrap mb-8">
          <div class="font-bold text-2xl text-gray-800 mr-2">
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
        :index="`/dashboard/collectivity/${collectivity.id}/history`"
      />
    </div>
    <TableActivities :table-data="tableData" />
    <div class="m-3 flex items-center">
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
    </div>
  </div>
</template>

<script>
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithFilters],
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
  async fetch() {
    const { data } = await this.$api.fetchActivities({
      'filter[subject_id]': this.$route.params.id,
      'filter[subject_type]': 'Collectivity',
      page: this.$route.query.page || 1,
    })
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
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
