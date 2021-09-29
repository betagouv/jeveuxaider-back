<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-[1.75rem] text-[#242526]">
          Module d'assignation RNA pour les organisations
        </div>
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
        <el-badge v-if="activeFilters" :value="activeFilters" type="primary">
          <el-button
            icon="el-icon-s-operation"
            class="!ml-4"
            @click="showFilters = !showFilters"
          >
            Filtres avancés
          </el-button>
        </el-badge>
        <el-button
          v-else
          icon="el-icon-s-operation"
          class="!ml-4"
          @click="showFilters = !showFilters"
        >
          Filtres avancés
        </el-button>
      </div>
      <div v-if="showFilters" class="flex flex-wrap gap-4 mb-4">
        <SearchFiltersQuery
          name="rna"
          label="RNA"
          :value="query['filter[rna]']"
          :options="[
            { label: 'Non renseigné', value: 'empty' },
            { label: 'Non applicable', value: 'na' },
            { label: 'Renseigné', value: 'filled' },
          ]"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="api_id"
          label="Établissement"
          :value="query['filter[api_id]']"
          :options="[
            { label: 'Non renseigné', value: 'empty' },
            { label: 'Non applicable', value: 'na' },
            {
              label: 'Non répertorié sur l\'API Engagement',
              value: 'not_found_api_engagement',
            },
            { label: 'Renseigné', value: 'filled' },
          ]"
          @changed="onFilterChange"
        />
        <SearchFiltersQueryInput
          name="lieu"
          label="Lieu"
          placeholder="Ville ou code postal"
          :initial-value="query['filter[lieu]']"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="$store.getters.taxonomies.structure_workflow_states.terms"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          v-if="$store.getters.contextRole === 'admin'"
          name="department"
          label="Département"
          multiple
          :value="query['filter[department]']"
          :options="
            $store.getters.taxonomies.departments.terms.map((term) => {
              return {
                label: `${term.value} - ${term.label}`,
                value: term.value,
              }
            })
          "
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table
      ref="table"
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
            <v-clamp :max-lines="1" autoresize>{{ scope.row.name }} </v-clamp>
          </client-only>
          <div v-if="scope.row.statut_juridique" class="text-secondary text-xs">
            {{ scope.row.statut_juridique }}
          </div>
          <div v-if="scope.row.full_address" class="text-secondary text-xs">
            {{ scope.row.full_address }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Contextes" min-width="300">
        <template slot-scope="scope">
          <div class="flex flex-wrap">
            <TagModelState
              v-if="scope.row.state"
              :state="scope.row.state"
              size="big"
            />
            <el-tag v-if="scope.row.department" type="info" class="m-1 ml-0">
              {{ scope.row.department | fullDepartmentFromValue }}
            </el-tag>
            <el-tag
              v-if="scope.row.missions_count"
              type="info"
              class="m-1 ml-0"
            >
              {{ scope.row.missions_count }}
              {{
                scope.row.missions_count | pluralize(['mission', 'missions'])
              }}
            </el-tag>
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="rna" label="API" width="320">
        <template slot-scope="scope">
          <div v-if="scope.row.rna" class="">RNA: {{ scope.row.rna }}</div>
          <template v-if="scope.row.api_id">
            <a
              v-if="scope.row.api_id != 'N/A'"
              :href="`https://api-association.cleverapps.io/association/${scope.row.rna}/etablissement/${scope.row.api_id}`"
              class=""
              target="_blank"
              >Fiche: {{ scope.row.api_id }}</a
            >
            <span v-else>Fiche: {{ scope.row.api_id }}</span>
          </template>
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
      <VoletRna @updated="onUpdatedRow" />
    </portal>
  </div>
</template>

<script>
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithVolet, TableWithFilters],
  layout: 'dashboard',
  asyncData({ $api, params, store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
  data() {
    return {
      loadingExport: false,
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchStructuresWithoutRna(this.query)
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
  },
  methods: {
    async onUpdatedRow() {
      await this.$fetch()
      if (this.totalRows) {
        // @TODO CLICK
        this.$refs.table.setCurrentRow(this.tableData[0])
        this.onClickedRow(this.tableData[0])
      }
    },
  },
}
</script>
