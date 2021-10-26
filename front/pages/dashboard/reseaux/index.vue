<template>
  <div class="structures has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-[1.75rem] text-[#242526]">Réseaux</div>
      </div>
      <div>
        <nuxt-link
          v-if="$store.getters.contextRole == 'admin'"
          to="/dashboard/reseaux/add"
        >
          <el-button type="primary">Créer un réseau</el-button>
        </nuxt-link>
      </div>
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
      <el-table-column prop="name" label="Réseau" min-width="320">
        <template slot-scope="scope">
          <client-only>
            <v-clamp :max-lines="1" autoresize>{{ scope.row.name }}</v-clamp>
          </client-only>
          <div class="text-sm text-secondary break-normal">
            {{ scope.row.structures_count }}
            {{
              scope.row.structures_count | pluralize(['antenne', 'antennes'])
            }}
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="nb_missions" label="Missions" width="250">
        <template slot-scope="scope">
          <div class="text-sm text-secondary break-normal">
            {{ scope.row.missions_count }}
            {{ scope.row.missions_count | pluralize(['mission', 'missions']) }}
          </div>
          <!-- <div class="text-sm text-secondary break-normal">
            {{ scope.row.mission_templates_count }}
            {{
              scope.row.mission_templates_count
                | pluralize(['modèle', 'modèles'])
            }}
          </div> -->
        </template>
      </el-table-column>
      <el-table-column label="Modifiée le" width="200">
        <template slot-scope="scope">
          <div class="text-sm text-secondary break-normal">
            {{ scope.row.updated_at | fromNow }}
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
    <portal to="volet">
      <VoletReseau @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithVolet, TableWithFilters],
  layout: 'dashboard',
  asyncData({ store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchReseaux({
      ...this.query,
    })
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
  },
}
</script>
