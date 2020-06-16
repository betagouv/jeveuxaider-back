<template>
  <div class="structures has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Corbeille - Structures
          <div class="text-gray-600 text-sm font-normal mt-2">
            Affiche les structures supprimés par les référents
          </div>
        </div>
      </div>
    </div>
    <div class="px-12 mb-6 -mt-6">
      <trash-menu></trash-menu>
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
      <el-table-column label="Nom" min-width="320">
        <template slot-scope="scope">
          <span v-if="type != 'Participations'">{{ scope.row.name }}</span>
          <span v-else>
            <template v-if="scope.row.profile">{{
              scope.row.profile.full_name
            }}</template>
            <template v-else>Profil supprimé</template>
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Type" min-width="120">
        {{ type }}
      </el-table-column>
      <el-table-column label="Date de suppression" min-width="200">
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
import { destroyMission } from '@/api/mission'
import { destroyParticipation } from '@/api/participation'
import TableWithFilters from '@/mixins/TableWithFilters'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import TrashMenu from '@/components/TrashMenu.vue'

export default {
  name: 'TrashStructures',
  components: { QueryMainSearchFilter, TrashMenu },
  mixins: [TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
    }
  },
  methods: {
    handleDelete(row) {
      this.loading = true
      if (this.type == 'Structures') {
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
      } else if (this.type == 'Missions') {
        destroyMission(row.id)
          .then(() => {
            this.loading = false
            this.$message({
              type: 'success',
              message: 'La mission a été défitivement supprimée',
            })
            let foundIndex = this.tableData.findIndex((el) => el.id === row.id)
            this.tableData.splice(foundIndex, 1)
          })
          .catch(() => {
            this.loading = false
          })
      } else if (this.type == 'Participations') {
        destroyParticipation(row.id)
          .then(() => {
            this.loading = false
            this.$message({
              type: 'success',
              message: 'La participation a été défitivement supprimée',
            })
            let foundIndex = this.tableData.findIndex((el) => el.id === row.id)
            this.tableData.splice(foundIndex, 1)
          })
          .catch(() => {
            this.loading = false
          })
      }
    },
    fetchRows() {
      return fetchTrashItems(this.query)
    },
  },
}
</script>
