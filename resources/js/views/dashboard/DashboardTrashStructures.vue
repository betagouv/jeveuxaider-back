<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Corbeille - Organisations
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <trash-menu index="/dashboard/trash/structures"></trash-menu>
    </div>

    <div class="px-12 mb-3 flex">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="search"
          label="Recherche"
          placeholder="Mots clés, etc..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
      </div>
    </div>

    <el-table
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
    >
      <el-table-column label="#" min-width="70" align="center">
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column label="Structure" min-width="320">
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column label="Statut" min-width="200">
        <template slot-scope="scope">
          {{ scope.row.state }}
        </template>
      </el-table-column>
      <el-table-column label="Créé le" min-width="200">
        <template slot-scope="scope">
          {{ scope.row.created_at }}
        </template>
      </el-table-column>
      <el-table-column label="Supprimé le" min-width="200">
        <template slot-scope="scope">
          {{ scope.row.deleted_at }}
        </template>
      </el-table-column>
      <el-table-column label="Actions" width="230">
        <template slot-scope="scope">
          <button
            type="button"
            class="el-button is-plain el-button--danger el-button--mini"
            @click="handleDelete(scope.row)"
          >
            Supprimer définitivement
          </button>
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
      />
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>
  </div>
</template>

<script>
import { fetchTrashItems } from '@/api/app'
import { destroyStructure } from '@/api/structure'
import TableWithFilters from '@/mixins/TableWithFilters'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import TrashMenu from '@/components/TrashMenu.vue'

export default {
  name: 'DashboardTrashStructures',
  components: { QueryMainSearchFilter, TrashMenu },
  mixins: [TableWithFilters],
  data() {
    return {
      type: 'structures',
      loading: true,
      tableData: [],
    }
  },
  methods: {
    fetchRows() {
      return fetchTrashItems(this.type, this.query)
    },
    handleDelete(row) {
      this.loading = true
      destroyStructure(row.id)
        .then(() => {
          this.loading = false
          this.$message({
            type: 'success',
            message: 'La structure a été défitivement supprimée',
          })
          let foundIndex = this.tableData.findIndex((el) => el.id === row.id)
          this.tableData.splice(foundIndex, 1)
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>
