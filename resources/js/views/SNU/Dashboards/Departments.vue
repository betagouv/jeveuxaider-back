<template>
  <div class="dashboard has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord - Départements
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <dashboard-menu index="departments" />
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="search"
          placeholder="Rechercher par mots clés..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table :data="tableData" style="width: 100%;">
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
          <div class="font-light text-gray-600 text-xs">
            <template v-if="scope.row.published">Publiée</template>
            <template v-else>Non publiée</template>
          </div>
        </template>
      </el-table-column>
      <el-table-column
        prop="structures_count"
        label="Orga."
        width="100"
        align="center"
      >
        <template slot-scope="scope">
          <span class="text-gray-500">{{
            scope.row.structures_count | formatNumber
          }}</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="missions_count"
        label="Miss."
        width="90"
        align="center"
      >
        <template slot-scope="scope">
          <span class="text-gray-500">{{
            scope.row.missions_count | formatNumber
          }}</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="participations_count"
        label="Partic."
        width="100"
        align="center"
      >
        <template slot-scope="scope">
          <span class="text-gray-500">{{
            scope.row.participations_count | formatNumber
          }}</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="volontaires_count"
        label="Benev."
        width="100"
        align="center"
      >
        <template slot-scope="scope">
          <div class="text-gray-500">
            {{ scope.row.volontaires_count | formatNumber }}
          </div>
          <div class="font-light text-gray-500 text-xs">
            {{ scope.row.service_civique_count | formatNumber }} en SC
          </div>
        </template>
      </el-table-column>
      <el-table-column
        prop="missions_available"
        label="Miss. dispos."
        width="140"
        align="center"
      >
        <template slot-scope="scope">
          <span class="text-gray-500">{{
            scope.row.missions_available | formatNumber
          }}</span>
        </template>
      </el-table-column>
      <el-table-column prop="places" label="Places" width="100" align="center">
        <template slot-scope="scope">
          <span class="text-gray-500">{{
            scope.row.places | formatNumber
          }}</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="taux_occupation"
        label="Occupation"
        width="130"
        align="center"
      >
        <template slot-scope="scope">
          <span class="text-gray-500">{{ scope.row.taux_occupation }}%</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="places_available"
        label="Places dispos."
        width="170"
        align="center"
      >
        <template slot-scope="scope">
          <el-tag :type="type(scope.row.places_available)">
            <template v-if="scope.row.places_available > 0">
              {{ scope.row.places_available | formatNumber }}
              {{ scope.row.places_available | pluralize(['place', 'places']) }}
            </template>
            <template v-else>
              Aucune place
            </template>
          </el-tag>
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
        <el-button icon="el-icon-download" size="small" @click="onExport">
          Export
        </el-button>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardMenu from '@/components/DashboardMenu'
import { statisticsDepartments, exportStatistics } from '@/api/app'
import { Message } from 'element-ui'
import fileDownload from 'js-file-download'
import TableWithFilters from '@/mixins/TableWithFilters'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'

export default {
  name: 'DashboardDepartments',
  components: {
    QueryMainSearchFilter,
    DashboardMenu,
  },
  mixins: [TableWithFilters],
  data() {
    return {
      loading: true,
    }
  },
  computed: {},
  methods: {
    fetchRows() {
      return statisticsDepartments(this.query)
    },
    onExport() {
      exportStatistics('departments', this.query)
        .then((response) => {
          this.loading = false
          fileDownload(response.data, 'departments.csv')
        })
        .catch((error) => {
          Message({
            message: error.response.data.message,
            type: 'error',
          })
        })
    },
    type(places) {
      if (places < 10) {
        return 'danger'
      } else if (places < 500) {
        return 'warning'
      } else {
        return 'info'
      }
    },
  },
}
</script>
