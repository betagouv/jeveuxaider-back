<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-2-5xl text-gray-800">Territoires</div>
      </div>
      <div class>
        <nuxt-link :to="`/dashboard/territoire/add`">
          <el-button type="primary"> Ajouter un territoire </el-button>
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
            class="ml-4"
            @click="showFilters = !showFilters"
          >
            Filtres avancés
          </el-button>
        </el-badge>
        <el-button
          v-else
          icon="el-icon-s-operation"
          class="ml-4"
          @click="showFilters = !showFilters"
        >
          Filtres avancés
        </el-button>
      </div>
      <div v-if="showFilters" class="flex flex-wrap gap-4 mb-4">
        <SearchFiltersQuery
          name="type"
          label="Type"
          :value="query['filter[type]']"
          :options="$store.getters.taxonomies.territoires_types.terms"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="$store.getters.taxonomies.territoires_states.terms"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="is_published"
          label="Publiée"
          :value="query['filter[is_published]']"
          :options="[
            { label: 'Oui', value: true },
            { label: 'Non', value: false },
          ]"
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
          <div class="text-secondary text-sm">
            {{ scope.row.id }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Territoire" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.name }}
          </div>
          <div class="font-light text-gray-600 text-xs flex items-center">
            <div
              :class="scope.row.is_published ? 'bg-green-500' : 'bg-red-500'"
              class="rounded-full h-2 w-2 mr-2 flex-none"
            />
            <nuxt-link
              v-if="scope.row.is_published"
              :to="scope.row.full_url"
              target="_blank"
              class="hover:underline"
            >
              {{ scope.row.full_url }}
            </nuxt-link>
            <span v-else class="cursor-default">
              {{ scope.row.full_url }}
            </span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Taux de complétion" width="200">
        <template slot-scope="scope">
          <el-progress
            :percentage="scope.row.completion_rate"
            :show-text="false"
            style="max-width: 130px"
          />
        </template>
      </el-table-column>
      <el-table-column label="Modifiée le" width="200">
        <template slot-scope="scope">
          <div class="text-sm text-gray-600">
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
          <DropdownTerritoireState
            :territoire="scope.row"
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
    <portal to="volet">
      <VoletTerritoire @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithFilters, TableWithVolet],
  layout: 'dashboard',
  asyncData({ store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchTerritoires({
      ...this.query,
      append: 'completion_rate,full_url',
    })
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
  },
  methods: {},
}
</script>
