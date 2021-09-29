<template>
  <div class="structures has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-[1.75rem] text-[#242526]">
          Organisations
        </div>
      </div>
      <div>
        <nuxt-link
          v-if="$store.getters.contextRole == 'admin'"
          to="/dashboard/structure/add"
        >
          <el-button type="primary">Créer une organisation</el-button>
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
          name="statut_juridique"
          label="Statut juridique"
          :value="query['filter[statut_juridique]']"
          :options="$store.getters.taxonomies.structure_legal_status.terms"
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
    <portal to="volet">
      <VoletStructure @updated="onUpdatedRow" @deleted="onDeletedRow" />
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
    if (
      !['admin', 'referent', 'referent_regional'].includes(
        store.getters.contextRole
      )
    ) {
      return error({ statusCode: 403 })
    }
  },
  data() {
    return {
      loadingExport: false,
    }
  },
  async fetch() {
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
  watch: {
    '$route.query': '$fetch',
  },
  methods: {
    onExport() {
      this.loadingExport = true
      this.$api
        .exportStructures(this.query)
        .then(() => {
          this.loadingExport = false
          // fileDownload(response.data, 'organisation.xlsx')
          this.$message.success({
            message:
              "Votre export est en cours de génération... Vous recevrez un e-mail lorsqu'il sera prêt !",
          })
        })
        .catch((error) => {
          this.$message.error({
            message: error.response.data.message,
          })
        })
    },
  },
}
</script>
