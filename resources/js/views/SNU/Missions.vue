<template>
  <div class="missions has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">{{ $store.getters["user/contextRoleLabel"] }}</div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Missions</div>
      </div>
      <div>
        <router-link
          v-if="$store.getters.contextRole === 'responsable'"
          :to="{
            name: 'MissionFormAdd',
            params: {
              structureId: $store.getters.structure_as_responsable.id
            }
          }"
        >
          <el-button type="primary" icon="el-icon-plus">Nouvelle mission</el-button>
        </router-link>
      </div>
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
        <query-search-filter
          name="lieu"
          label="Lieu"
          placeholder="Ville ou code postal"
          :value="query['filter[lieu]']"
          @changed="onFilterChange"
        />
        <query-filter
          v-if="$store.getters.contextRole === 'admin'"
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
          name="type"
          label="Type de mission"
          :value="query['filter[type]']"
          :options="$store.getters.taxonomies.mission_types.terms"
          @changed="onFilterChange"
        />
        <query-filter
          name="format"
          label="Format de mission"
          :value="query['filter[format]']"
          :options="$store.getters.taxonomies.mission_formats.terms"
          @changed="onFilterChange"
        />
        <query-filter
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="$store.getters.taxonomies.mission_workflow_states.terms"
          @changed="onFilterChange"
        />
        <query-filter
          type="select"
          name="name"
          :value="query['filter[name]']"
          label="Domaine"
          :options="$store.getters.taxonomies.mission_domaines.terms"
          @changed="onFilterChange"
        />
        <query-filter
          name="place"
          label="Places restantes"
          :value="query['filter[place]']"
          :options="[
            { label: 'Oui', value: true },
            { label: 'Non', value: false }
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
          <el-avatar
            v-if="scope.row.structure && scope.row.structure.logo"
            :src="`${scope.row.structure.logo}`"
            class="w-10 rounded-full border"
          />
          <el-avatar v-else class="bg-primary">{{ scope.row.structure.name[0] }}</el-avatar>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Mission" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">{{ scope.row.name|labelFromValue('mission_domaines') }}</div>
          <div v-if="scope.row.structure" class="font-light text-gray-600 flex items-center">
            <div class="text-xs">{{ scope.row.structure.name }}</div>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Dates" width="160">
        <template slot-scope="scope">
          <div v-if="scope.row.start_date" class>
            <span class="text-gray-400 mr-1 text-xs">Du</span>
            {{ scope.row.start_date | formatMedium }}
          </div>
          <div v-if="scope.row.end_date" class>
            <span class="text-gray-400 mr-1 text-xs">Au</span>
            {{ scope.row.end_date | formatMedium }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Ville" width="185">
        <template slot-scope="scope">
          <div v-if="scope.row.city" class>{{ scope.row.city | cleanCity }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Places" width="130">
        <template slot-scope="scope">
          <div v-if="scope.row.has_places_left">
            {{ scope.row.participations_max - scope.row.participations_count }}
            {{
            (scope.row.participations_max - scope.row.participations_count)
            | pluralize(["place", "places"])
            }}
          </div>
          <div v-else>Complet</div>
          <div
            class="font-light text-gray-600 text-xs"
          >{{ scope.row.participations_count }} / {{ scope.row.participations_max }}</div>
        </template>
      </el-table-column>
      <el-table-column prop="state" label="Statut" min-width="170">
        <template slot-scope="scope">
          <state-tag :state="scope.row.state"></state-tag>
        </template>
      </el-table-column>
      <el-table-column v-if="!$store.getters['volet/active']" label="Actions" width="165">
        <template slot-scope="scope">
          <el-dropdown
            v-if="canClone()"
            size="small"
            split-button
            trigger="click"
            @click="
              $router.push({
                name: 'MissionFormEdit',
                params: { id: scope.row.id }
              })
            "
            @command="handleCommand"
          >
            <i class="el-icon-edit mr-2"></i>Modifier
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item :command="`/missions/${scope.row.id}`">Visualiser</el-dropdown-item>
              <!-- <el-dropdown-item :command="{ action: 'clone', id: scope.row.id }">Dupliquer</el-dropdown-item> -->
            </el-dropdown-menu>
          </el-dropdown>
          <router-link v-else :to="{ name: 'MissionFormEdit', params: { id: scope.row.id } }">
            <el-button icon="el-icon-edit" size="mini" class="m-1">Modifier</el-button>
          </router-link>
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
        <el-button icon="el-icon-download" size="small" @click="onExport">Export</el-button>
      </div>
    </div>
    <portal to="volet">
      <mission-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchMissions, exportMissions, cloneMission } from "@/api/mission";
import StateTag from "@/components/StateTag";
import TableWithFilters from "@/mixins/TableWithFilters";
import TableWithVolet from "@/mixins/TableWithVolet";
import QueryFilter from "@/components/QueryFilter.vue";
import QuerySearchFilter from "@/components/QuerySearchFilter.vue";
import QueryMainSearchFilter from "@/components/QueryMainSearchFilter.vue";
import MissionVolet from "@/layout/components/Volet/MissionVolet.vue";
import fileDownload from "js-file-download";

export default {
  name: "Missions",
  components: {
    StateTag,
    QueryFilter,
    QuerySearchFilter,
    QueryMainSearchFilter,
    MissionVolet
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
      return fetchMissions(this.query);
    },
    onExport() {
      this.loading = true;
      exportMissions(this.query)
        .then(response => {
          this.loading = false;
          fileDownload(response.data, "missions.xlsx");
        })
        .catch(error => {
          console.log(error);
        });
    },
    clone(id) {
      this.loading = true;
      cloneMission(id).then(response => {
        this.$router
          .push({
            path: `/dashboard/mission/${response.data.id}/edit`
          })
          .then(() => {
            this.$message({
              message: "La mission a été dupliquée !",
              type: "success"
            });
          });
      });
    },
    handleCommand(command) {
      if (command.action == "clone") {
        this.clone(command.id);
      } else {
        this.$router.push(command);
      }
    },
    canClone() {
      return false // Fonctionnalité désactivée car bugs remontés dans Sentry
      let roles = ["admin", "referent", "responsable"];
      return roles.includes(this.$store.getters.contextRole);
    }
  }
};
</script>
