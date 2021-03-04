<template>
  <div class="dashboard has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord - Départements
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <DashboardTabsMain index="departments" />
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <SearchFiltersQueryMain
          name="search"
          placeholder="Rechercher par mots clés..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table v-loading="loading" :data="tableData" style="width: 100%">
      <el-table-column width="70" label="#" align="center"
        ><template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.key }}
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="label" label="Département">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.name }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Organisations" width="130" align="center">
        <template slot-scope="scope">
          <div class="text-primary">
            {{ scope.row.structures_count | formatNumber }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Missions" width="100" align="center">
        <template slot-scope="scope">
          <div class="text-primary">
            {{ scope.row.missions_count | formatNumber }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Participations" width="130" align="center">
        <template slot-scope="scope">
          <div class="text-primary">
            {{ scope.row.participations_count | formatNumber }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Bénévoles" width="160" align="center">
        <template slot-scope="scope">
          <div class="text-primary">
            {{ scope.row.volontaires_count | formatNumber }}
          </div>
          <div class="text-xs">
            {{ scope.row.service_civique_count | formatNumber }}
            <span class="text-gray-500">en SC</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Places disponibles" width="200" align="center">
        <template slot-scope="scope">
          <div class="text-primary">
            {{ scope.row.places_available | formatNumber }}
          </div>
          <div class="text-xs">
            {{ scope.row.missions_available | formatNumber }}
            <span class="text-gray-500">missions disponibles</span>
          </div>
          <div class="text-xs">
            {{ scope.row.organisations_active | formatNumber }}
            <span class="text-gray-500">organisations actives</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Taux d'occupation" width="200" align="center">
        <template slot-scope="scope">
          <div class="text-primary">
            {{ scope.row.occupation_rate | formatNumber }}%
          </div>
          <div class="text-xs">
            {{ scope.row.total_offered_places | formatNumber }}
            <span class="text-gray-500">places offertes</span>
          </div>
        </template>
      </el-table-column>
    </el-table>
    <div class="m-3 flex items-center">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="totalRows"
        :page-size="15"
        :current-page="Number(query.page)"
        @current-change="onPageChange"
      ></el-pagination>
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
      <div class="ml-auto">
        <el-button
          :loading="loadingExport"
          icon="el-icon-download"
          size="small"
          @click="onExport"
        >
          Export
        </el-button>
      </div>
    </div>
  </div>
</template>

<script>
import { Message } from 'element-ui'
import fileDownload from 'js-file-download'
import TableWithFilters from '@/mixins/TableWithFilters'

export default {
  mixins: [TableWithFilters],
  layout: 'dashboard',
  data() {
    return {
      loading: true,
      loadingExport: false,
    }
  },
  computed: {},
  methods: {
    fetchRows() {
      return this.$api.statisticsDepartments(this.query)
    },
    onExport() {
      this.loadingExport = true
      this.$api
        .exportStatistics('departments', this.query)
        .then((response) => {
          this.loadingExport = false
          fileDownload(response.data, 'departments.csv')
        })
        .catch((error) => {
          Message({
            message: error.response.data.message,
            type: 'error',
          })
        })
    },
  },
}
</script>
