<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Activités</div>
      </div>
    </div>

    <div class="flex flex-wrap px-12 mb-3">
      <query-search-filter
        name="subject_id"
        label="ID de l'objet"
        placeholder="ex: 1494"
        :initial-value="query['filter[subject_id]']"
        @changed="onFilterChange"
      />
      <query-filter
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
import { fetchActivities } from '@/api/app'
import TableWithFilters from '@/mixins/TableWithFilters'
import QueryFilter from '@/components/QueryFilter.vue'
import QuerySearchFilter from '@/components/QuerySearchFilter.vue'
import TableActivities from '@/components/TableActivities.vue'

export default {
  name: 'DashboardActivities',
  components: {
    QueryFilter,
    QuerySearchFilter,
    TableActivities,
  },
  mixins: [TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
      subjectTypes: [
        { label: 'Mission', value: 'Mission' },
        { label: 'Organisation', value: 'Structure' },
        { label: 'Participation', value: 'Participation' },
        { label: 'Utilisateur', value: 'Profile' },
      ],
    }
  },
  methods: {
    fetchRows() {
      return fetchActivities(this.query)
    },
  },
}
</script>
