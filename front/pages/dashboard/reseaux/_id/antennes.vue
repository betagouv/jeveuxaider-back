<template>
  <div>
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-[1.75rem] text-[#242526]">Antennes</div>
      </div>
      <div>
        <nuxt-link to="/reseaux/id/antennes/add">
          <el-button type="primary">Créer une antenne</el-button>
        </nuxt-link>
      </div>
    </div>
    <el-table
      v-loading="$fetchState.pending"
      :data="tableData"
      :highlight-current-row="true"
      @row-click="onClickedRow"
    >
      <el-table-column width="70" label="Id" align="center">
        <template slot-scope="scope">
          <div class="text-secondary text-sm">{{ scope.row.id }}</div>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Organisation" min-width="320">
        <template slot-scope="scope">
          <client-only>
            <v-clamp :max-lines="1" autoresize>{{ scope.row.name }}</v-clamp>
          </client-only>
          <div v-if="scope.row.statut_juridique" class="text-secondary text-xs">
            {{ scope.row.statut_juridique }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Contextes" min-width="300">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.is_reseau" class="m-1 ml-0" type="info">
            Tête de réseau
          </el-tag>
          <el-tag v-if="scope.row.reseau_id" class="m-1 ml-0">
            {{ scope.row.reseau_id | reseauFromValue }}
          </el-tag>
          <el-tag v-if="scope.row.department" type="info" class="m-1 ml-0">
            {{ scope.row.department | fullDepartmentFromValue }}
          </el-tag>
          <nuxt-link
            v-if="scope.row.missions_count > 0"
            :to="`/dashboard/structure/${scope.row.id}/missions`"
          >
            <el-tag type="info" class="m-1 ml-0">
              {{ scope.row.missions_count }}
              {{
                scope.row.missions_count | pluralize(['mission', 'missions'])
              }}
            </el-tag>
          </nuxt-link>
        </template>
      </el-table-column>
      <el-table-column label="Modifiée le" min-width="150">
        <template slot-scope="scope">
          <div class="text-sm text-secondary break-normal">
            {{ scope.row.updated_at | fromNow }}
          </div>
        </template>
      </el-table-column>
      <el-table-column
        prop="state"
        label="Statut"
        width="250"
        class-name="dropdown-wrapper"
      >
        <template slot-scope="scope">
          <DropdownStructureState
            :structure="scope.row"
            @updated="onUpdatedRow"
          />
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
  mixins: [TableWithVolet, TableWithFilters],
  layout: 'dashboard',
  asyncData({ $api, params, store, error }) {
    if (!['admin', 'tete_de_reseau'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }

    if (store.getters.contextRole == 'tete_de_reseau') {
      if (store.getters.profile.tete_de_reseau_id != params.id) {
        return error({ statusCode: 403 })
      }
    }
  },
  data() {
    return {
      loading: true,
      mission: null,
      structure: null,
      tableData: [],
    }
  },
  async fetch() {
    console.log('query', this.query)
    const { data } = await this.$api.fetchStructures({
      ...this.query,
      include: 'missionsCount',
      append: 'completion_rate',
    })
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
}
</script>
