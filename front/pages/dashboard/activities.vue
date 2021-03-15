<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Activités</div>
      </div>
    </div>

    <div class="flex flex-wrap px-12 mb-3">
      <SearchFiltersQueryInput
        name="subject_id"
        label="ID de l'objet"
        placeholder="ex: 1494"
        :initial-value="query['filter[subject_id]']"
        @changed="onFilterChange"
      />
      <SearchFiltersQuery
        type="select"
        name="subject_type"
        :value="query['filter[subject_type]']"
        label="Type de l'objet"
        :options="subjectTypes"
        @changed="onFilterChange"
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
  asyncData({ store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
  data() {
    return {
      subjectTypes: [
        { label: 'Mission', value: 'Mission' },
        { label: 'Organisation', value: 'Structure' },
        { label: 'Participation', value: 'Participation' },
        { label: 'Utilisateur', value: 'Profile' },
      ],
    }
  },
  async fetch() {
    this.query = this.$route.query
    const { data } = await this.$api.fetchActivities(this.query)
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
