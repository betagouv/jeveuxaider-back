<template>
  <div class="structures has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Structures
        </div>
      </div>
      <div>
        <router-link
          v-if="$store.getters.contextRole == 'admin'"
          :to="{ name: 'StructureFormAdd' }"
        >
          <el-button type="primary" icon="el-icon-plus">
            Nouvelle structure
          </el-button>
        </router-link>
      </div>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
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
      <div v-if="showFilters" class="flex flex-wrap">
        <query-search-filter
          name="lieu"
          label="Lieu"
          placeholder="Ville ou code postal"
          :initial-value="query['filter[lieu]']"
          @changed="onFilterChange"
        />
        <query-filter
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="$store.getters.taxonomies.structure_workflow_states.terms"
          @changed="onFilterChange"
        />
        <query-filter
          name="statut_juridique"
          label="Statut juridique"
          :value="query['filter[statut_juridique]']"
          :options="$store.getters.taxonomies.structure_legal_status.terms"
          @changed="onFilterChange"
        />
        <query-filter
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
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
      @row-click="onClickedRow"
    >
      <el-table-column width="70" align="center">
        <template slot-scope="scope">
          <el-avatar class="bg-primary">
            {{ scope.row.name[0] }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Structure" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.name }}
          </div>
          <div
            v-if="scope.row.statut_juridique"
            class="font-light text-gray-600 text-xs"
          >
            {{ scope.row.statut_juridique }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Contextes" min-width="320">
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
          <el-tag
            v-if="scope.row.missions_count > 0"
            type="info"
            class="m-1 ml-0"
          >
            {{ scope.row.missions_count }}
            {{ scope.row.missions_count | pluralize(['mission', 'missions']) }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="state" label="Statut" min-width="220">
        <template slot-scope="scope">
          <structure-dropdown-state
            :form="scope.row"
            @updated="onUpdatedRow"
          ></structure-dropdown-state>
        </template>
      </el-table-column>
      <el-table-column
        v-if="!$store.getters['volet/active']"
        label="Actions"
        width="250"
      >
        <template slot-scope="scope">
          <el-dropdown size="small" split-button>
            Choisissez une action
            <el-dropdown-menu slot="dropdown">
              <router-link
                :to="{
                  name: 'Structure',
                  params: { id: scope.row.id },
                }"
              >
                <el-dropdown-item>Visualiser la structure</el-dropdown-item>
              </router-link>
              <router-link
                :to="{
                  name: 'StructureFormEdit',
                  params: { id: scope.row.id },
                }"
              >
                <el-dropdown-item>Modifier la structure</el-dropdown-item>
              </router-link>
              <router-link
                :to="{
                  name: 'MissionFormAdd',
                  params: { structureId: scope.row.id },
                }"
              >
                <el-dropdown-item>Ajouter une mission</el-dropdown-item>
              </router-link>
              <router-link
                :to="{
                  name: 'StructureMembers',
                  params: { id: scope.row.id },
                }"
              >
                <el-dropdown-item divided>
                  Gérer l'équipe
                </el-dropdown-item>
              </router-link>
            </el-dropdown-menu>
          </el-dropdown>
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
        <el-button icon="el-icon-download" size="small" @click="onExport">
          Export
        </el-button>
      </div>
    </div>
    <portal to="volet">
      <structure-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchStructures, exportStructures } from '@/api/structure'
import TableWithVolet from '@/mixins/TableWithVolet'
import TableWithFilters from '@/mixins/TableWithFilters'
import StructureVolet from '@/layout/components/Volet/StructureVolet'
import QueryFilter from '@/components/QueryFilter.vue'
import QuerySearchFilter from '@/components/QuerySearchFilter.vue'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import fileDownload from 'js-file-download'
import StateTag from '@/components/StateTag'
import StructureDropdownState from '@/components/StructureDropdownState'

export default {
  name: 'Structures',
  components: {
    StructureVolet,
    QueryFilter,
    QueryMainSearchFilter,
    QuerySearchFilter,
    StateTag,
    StructureDropdownState,
  },
  mixins: [TableWithVolet, TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
    }
  },
  methods: {
    fetchRows() {
      return fetchStructures(this.query)
    },
    onExport() {
      this.loading = true
      exportStructures(this.query)
        .then((response) => {
          this.loading = false
          fileDownload(response.data, 'structures.xlsx')
        })
        .catch((error) => {
          console.log(error)
        })
    },
  },
}
</script>
