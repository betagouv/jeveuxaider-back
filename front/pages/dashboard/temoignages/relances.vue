<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-[1.75rem] text-[#242526]">
          Témoignages - Relances
        </div>
      </div>
    </div>

    <div class="px-12 mb-12">
      <TabsTemoignages index="/dashboard/temoignages/relances" />
    </div>

    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <SearchFiltersQueryMain
          name="search"
          placeholder="Rechercher par email, nom..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
        <el-badge :value="activeFilters || null" type="primary">
          <el-button
            icon="el-icon-s-operation"
            class="!ml-4"
            @click="showFilters = !showFilters"
          >
            Filtres avancés
          </el-button>
        </el-badge>
      </div>
      <div v-if="showFilters" class="flex flex-wrap gap-4 mb-4">
        <SearchFiltersQueryInput
          name="participation.mission.id"
          label="# Mission"
          type="number"
          placeholder="Numéro"
          :initial-value="query['filter[participation.mission.id]']"
          @changed="onFilterChange"
        />
      </div>
    </div>

    <TableNotificationsTemoignages
      :loading="$fetchState.pending"
      :table-data="tableData"
      :on-updated-row="onUpdatedRow"
      :on-clicked-row="onClickedRow"
    />
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

    <portal to="volet">
      <VoletNotificationTemoignage @update="onUpdatedRow" />
    </portal>
  </div>
</template>

<script>
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithFilters, TableWithVolet],
  layout: 'dashboard',
  asyncData({ store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
  async fetch() {
    const { data } = await this.$axios.get(`/notifications-temoignages`, {
      params: this.query,
    })
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
}
</script>
