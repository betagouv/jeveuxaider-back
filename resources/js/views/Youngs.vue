<template>
  <div class="youngs has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters["user/contextRoleLabel"] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Volontaires
        </div>
      </div>
      <div>
        <router-link
          v-if="
            $store.getters.contextRole == 'admin' ||
              $store.getters.contextRole == 'referent'
          "
          :to="{ name: 'YoungFormAdd' }"
        >
          <el-button type="primary" icon="el-icon-plus"
            >Nouveau volontaire</el-button
          >
        </router-link>
      </div>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="search"
          placeholder="Rechercher par nom, prénom, email..."
          :value="query['filter[search]']"
          @changed="onFilterChange"
        />
        <el-badge v-if="activeFilters" :value="activeFilters" type="primary">
          <el-button
            icon="el-icon-s-operation"
            class="ml-4"
            @click="showFilters = !showFilters"
            >Filtres avancés</el-button
          >
        </el-badge>
        <el-button
          v-else
          icon="el-icon-s-operation"
          class="ml-4"
          @click="showFilters = !showFilters"
          >Filtres avancés</el-button
        >
      </div>
      <div v-if="showFilters" class="flex flex-wrap">
        <query-filter
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="$store.getters.taxonomies.young_workflow_states.terms"
          @changed="onFilterChange"
        />
        <query-search-filter
          name="lieu"
          label="Lieu"
          placeholder="Ville ou code postal"
          :value="query['filter[lieu]']"
          @changed="onFilterChange"
        />
        <query-filter
          v-if="$store.getters.contextRole == 'admin'"
          name="department"
          label="Département"
          multiple
          :value="query['filter[department]']"
          :options="
            $store.getters.taxonomies.departments.terms.map(term => {
              return {
                label: `${term.value} - ${term.label}`,
                value: term.value
              };
            })
          "
          @changed="onFilterChange"
        />
        <query-filter
          type="select"
          name="mission_format"
          :value="query['filter[mission_format]']"
          multiple
          label="Format"
          :options="$store.getters.taxonomies.mission_formats.terms"
          @changed="onFilterChange"
        />
        <query-filter
          v-if="
            $store.getters.contextRole == 'admin' ||
              $store.getters.contextRole == 'referent'
          "
          name="context"
          label="Context"
          :value="query['filter[context]']"
          :options="[
            { label: 'Email incorrect', value: 'Email incorrect' },
            { label: 'Non géolocalisé', value: 'Non géolocalisé' }
          ]"
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
            {{ scope.row.first_name[0] }}{{ scope.row.last_name[0] }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column label="Email" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.full_name }}
          </div>
          <div class="font-light text-gray-600">{{ scope.row.email }}</div>
        </template>
      </el-table-column>
      <el-table-column prop="state" label="Contextes" min-width="320">
        <template slot-scope="scope">
          <div class="flex flex-row flex-wrap -m-1">
            <state-tag :state="scope.row.state" size="small"></state-tag>
            <el-tag
              v-if="
                scope.row.department && $store.getters.contextRole == 'admin'
              "
              type="info"
              class="m-1"
              size="small"
            >
              {{ scope.row.department | fullDepartmentFromValue }}
            </el-tag>
            <el-tag
              v-if="!scope.row.regular_latitude && !scope.row.regular_longitude"
              type="danger"
              class="m-1"
              size="small"
            >
              Non géolocalisé
            </el-tag>
            <el-tag
              v-if="!RegExp('^[^@]+@[^@]+\.[^@]{2,}$').test(scope.row.email)"
              type="danger"
              class="m-1 ml-0"
              size="small"
            >
              Email incorrect
            </el-tag>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Mission" width="250">
        <template slot-scope="scope">
          <router-link
            v-if="!scope.row.mission_id"
            :to="{
              name: 'YoungAssignation',
              params: {
                id: scope.row.id
              }
            }"
            @click.native="$event.stopImmediatePropagation()"
          >
            <el-button icon="el-icon-search" size="mini"
              >Trouver une mission</el-button
            >
          </router-link>
          <div v-else>
            <div class="text-gray-900">{{ scope.row.mission.name }}</div>
            <div class="font-light text-gray-600">
              {{ scope.row.mission.structure.name }}
            </div>
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
      >
      </el-pagination>
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
      <div class="ml-auto">
        <el-button icon="el-icon-download" size="small" @click="onExport"
          >Export</el-button
        >
      </div>
    </div>
    <portal to="volet">
      <young-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchYoungs, exportYoungs } from "@/api/young";
import TableWithVolet from "@/mixins/TableWithVolet";
import TableWithFilters from "@/mixins/TableWithFilters";
import QueryFilter from "@/components/QueryFilter.vue";
import QueryMainSearchFilter from "@/components/QueryMainSearchFilter.vue";
import YoungVolet from "@/layout/components/Volet/YoungVolet.vue";
import fileDownload from "js-file-download";
import StateTag from "@/components/StateTag";

export default {
  name: "Youngs",
  components: { StateTag, YoungVolet, QueryFilter, QueryMainSearchFilter },
  mixins: [TableWithVolet, TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
      totalRows: 0
    };
  },
  beforeRouteUpdate(to, from, next) {
    this.query = { ...to.query };
    this.fetchDatas();
    next();
  },
  methods: {
    fetchRows() {
      return fetchYoungs(this.query);
    },
    onExport() {
      this.loading = true;
      exportYoungs(this.query)
        .then(response => {
          this.loading = false;
          fileDownload(response.data, "volontaires.xlsx");
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>
