<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="font-bold text-[1.75rem] text-[#242526]">Corbeille</div>
      </div>
    </div>

    <el-menu
      class="mb-8"
      :default-active="model"
      mode="horizontal"
      @select="handleChangeModel"
    >
      <el-menu-item index="structure">Organisations</el-menu-item>
      <el-menu-item index="mission">Missions</el-menu-item>
    </el-menu>

    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <SearchFiltersQueryMain
          name="search"
          placeholder="Rechercher par mots clés"
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
      </div>
    </div>

    <el-table
      :loading="$fetchState.pending"
      :data="tableData"
      :highlight-current-row="true"
    >
      <el-table-column label="#" min-width="70" align="center">
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column
        label-class-name="capitalize"
        :label="model"
        min-width="320"
      >
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
          {{ scope.row.created_at | formatMediumWithTime }}
        </template>
      </el-table-column>
      <el-table-column label="Supprimé le" min-width="200">
        <template slot-scope="scope">
          {{ scope.row.deleted_at | formatMediumWithTime }}
        </template>
      </el-table-column>
      <el-table-column label="Actions" width="430">
        <template slot-scope="scope">
          <div class="flex flex-shrink-0 space-x-3">
            <button
              type="button"
              class="el-button is-plain el-button--mini"
              @click="handleRestore(scope.row)"
            >
              Restaurer
            </button>
            <button
              type="button"
              class="el-button is-plain el-button--danger el-button--mini"
              @click="handleDelete(scope.row)"
            >
              Supprimer définitivement
            </button>
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
      />
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>
  </div>
</template>

<script>
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithFilters, TableWithVolet],
  layout: 'dashboard',
  asyncData({ store, error }) {
    if (store.getters.contextRole != 'admin') {
      return error({ statusCode: 403 })
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchTrashItems(this.model, this.query)
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  computed: {
    model() {
      return this.query && this.query.model ? this.query.model : 'structure'
    },
  },
  watch: {
    '$route.query': '$fetch',
  },
  mounted() {
    // console.log('query', this.query)
  },
  methods: {
    handleChangeModel(model) {
      this.$router.push(`/dashboard/corbeille?model=${model}`)
    },
    handleDelete(row) {
      if (this.model == 'structure') {
        this.destroyStructure(row)
      } else if (this.model == 'mission') {
        this.destroyMission(row)
      }
    },
    destroyStructure(row) {
      this.$api.destroyStructure(row.id).then(() => {
        this.$message({
          type: 'success',
          message: `La structure "${row.name}" a été défitivement supprimée`,
        })
        const foundIndex = this.tableData.findIndex((el) => el.id === row.id)
        this.tableData.splice(foundIndex, 1)
      })
    },
    destroyMission(row) {
      this.$api.destroyMission(row.id).then(() => {
        this.$message({
          type: 'success',
          message: `La mission "${row.name}" a été défitivement supprimée`,
        })
        const foundIndex = this.tableData.findIndex((el) => el.id === row.id)
        this.tableData.splice(foundIndex, 1)
      })
    },
    handleRestore(row) {
      if (this.model == 'structure') {
        this.restoreStructure(row)
      } else if (this.model == 'mission') {
        this.restoreMission(row)
      }
    },
    restoreStructure(row) {
      this.$api.restoreStructure(row.id).then(() => {
        this.$message({
          type: 'success',
          message: `La structure "${row.name}" a été restaurée`,
        })
        const foundIndex = this.tableData.findIndex((el) => el.id === row.id)
        this.tableData.splice(foundIndex, 1)
      })
    },
    restoreMission(row) {
      this.$api.restoreMission(row.id).then(() => {
        this.$message({
          type: 'success',
          message: `La mission "${row.name}" a été restaurée`,
        })
        const foundIndex = this.tableData.findIndex((el) => el.id === row.id)
        this.tableData.splice(foundIndex, 1)
      })
    },
  },
}
</script>

<style lang="postcss" scoped>
::v-deep {
  .el-table_1_column_2 .cell {
    text-transform: capitalize;
  }
}
</style>
