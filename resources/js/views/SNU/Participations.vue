<template>
  <div class="missions has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">{{ $store.getters["user/contextRoleLabel"] }}</div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Participations</div>
      </div>
      <div></div>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="search"
          placeholder="Rechercher par mots clés, mission ou structure..."
          :value="query['filter[search]']"
          @changed="onFilterChange"
        />
        <el-badge v-if="activeFilters" :value="activeFilters" type="primary">
          <el-button
            icon="el-icon-s-operation"
            class="ml-4"
            @click="showFilters = !showFilters"
          >Filtres avancés</el-button>
        </el-badge>
        <el-button
          v-else
          icon="el-icon-s-operation"
          class="ml-4"
          @click="showFilters = !showFilters"
        >Filtres avancés</el-button>
      </div>
      <div v-if="showFilters" class="flex flex-wrap">
        <query-filter
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="$store.getters.taxonomies.participation_workflow_states.terms"
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
          <el-avatar class="bg-primary">{{ scope.row.profile.short_name }}</el-avatar>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Volontaire" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">{{ scope.row.profile.full_name }}</div>
          <div class="font-light text-gray-600 flex items-center">
            <div class="text-xs">{{ scope.row.profile.email }} - {{ scope.row.profile.mobile }}</div>
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Mission" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">
            <v-clamp :max-lines="1" autoresize>{{ scope.row.mission.name }}</v-clamp>
          </div>
          <div class="font-light text-gray-600 flex items-center">
            <div class="text-xs">{{ scope.row.mission.structure.name }}</div>
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="state" label="Statut" min-width="170">
        <template slot-scope="scope">
          <state-tag :state="scope.row.state"></state-tag>
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="Crée le" min-width="120">
        <template slot-scope="scope">{{ scope.row.created_at | fromNow }}</template>
      </el-table-column>
      <el-table-column v-if="!$store.getters['volet/active']" label="Actions" width="165">
        <template slot-scope="scope">
          <el-button icon="el-icon-edit" size="mini" class="m-1">Modifier</el-button>
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
      ></el-pagination>
      <div
        class="text-secondary text-xs ml-3"
      >Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats</div>
      <div class="ml-auto">
        <el-button icon="el-icon-download" size="small" @click="onExport"
          >Export</el-button
        >
      </div>
    </div>
    <portal to="volet">
      <participation-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchParticipations, exportParticipations } from "@/api/participation";
import StateTag from "@/components/StateTag";
import TableWithFilters from "@/mixins/TableWithFilters";
import TableWithVolet from "@/mixins/TableWithVolet";
import QueryFilter from "@/components/QueryFilter.vue";
import QuerySearchFilter from "@/components/QuerySearchFilter.vue";
import QueryMainSearchFilter from "@/components/QueryMainSearchFilter.vue";
import ParticipationVolet from "@/layout/components/Volet/ParticipationVolet.vue";

import fileDownload from "js-file-download";

export default {
  name: "Participations",
  components: {
    StateTag,
    QueryFilter,
    QuerySearchFilter,
    QueryMainSearchFilter,
    ParticipationVolet
  },
  mixins: [TableWithFilters, TableWithVolet],
  data() {
    return {
      loading: true,
      tableData: []
    };
  },
  beforeRouteUpdate(to, from, next) {
    this.query = { ...to.query };
    this.fetchDatas();
    next();
  },
  methods: {
    fetchRows() {
      return fetchParticipations(this.query);
    },
    onExport() {
      this.loading = true;
      exportParticipations(this.query)
        .then(response => {
          this.loading = false;
          console.log('export', response.data)
          fileDownload(response.data, "participations.xlsx");
        })
        .catch(error => {
          console.log(error);
        });
    },
  }
};
</script>
