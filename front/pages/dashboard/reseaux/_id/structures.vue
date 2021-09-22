<template>
  <div>
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Réseau {{ reseau.name }}
        </div>
        <div class="mb-8 font-bold text-[1.75rem] text-[#242526]">
          Organisations
        </div>
      </div>
      <div>
        <nuxt-link to="/reseaux/id/structures/add">
          <el-button type="primary">Inviter une organisation</el-button>
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
        <el-badge
          v-if="activeFilters > 1"
          :value="activeFilters - 1"
          type="primary"
        >
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
  async asyncData({ $api, params, store, error }) {
    if (!['admin', 'tete_de_reseau'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }

    if (store.getters.contextRole == 'tete_de_reseau') {
      if (store.getters.profile.tete_de_reseau_id != params.id) {
        return error({ statusCode: 403 })
      }
    }

    const reseau = await $api.getReseau(params.id)

    return {
      reseau,
    }
  },
  data() {
    return {
      loading: true,
      tableData: [],
    }
  },
  async fetch() {
    this.query['filter[of_reseau]'] = this.$route.params.id
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
}
</script>
