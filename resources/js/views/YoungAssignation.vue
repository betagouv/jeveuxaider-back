<template>
  <div v-if="!$store.getters.loading" class="young-mission has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Trouver une mission pour ce volontaire ({{ young.regular_city }})
        </div>
        <div class="mb-8 flex">
          <div class="font-bold text-2xl text-gray-800">
            {{ young.first_name }} {{ young.last_name }}
          </div>
          <state-tag
            :state="young.state"
            class="relative ml-3"
            style="top: 1px"
          ></state-tag>
        </div>
      </div>
      <div>
        <el-button
          type="default"
          :icon="showInfos ? 'el-icon-view' : 'el-icon-s-fold'"
          class="mr-3"
          @click="showInfos = !showInfos"
        >
          <template v-if="showInfos">
            Masquer les préférences
          </template>
          <template v-else>
            Afficher les préférences
          </template>
        </el-button>
      </div>
      <router-link
        v-if="$store.getters.contextRole == 'admin'"
        :to="{ name: 'YoungFormEdit', params: { id: young.id } }"
      >
        <el-button type="secondary" icon="el-icon-edit"
          >Modifier la fiche</el-button
        >
      </router-link>
    </div>
    <div v-if="showInfos" class="px-12 mb-12">
      <div class="mb-6 text-2xl">
        Informations
      </div>
      <young-infos :young="young"></young-infos>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <query-filter
        name="ceu"
        label="Corps en uniforme"
        :value="query['filter[ceu]']"
        :options="[
          {
            value: true,
            label: 'Oui'
          },
          {
            value: false,
            label: 'Non'
          }
        ]"
        @changed="onFilterChange"
      />
      <query-filter
        type="select"
        name="domaines"
        :value="query['filter[domaines]']"
        multiple
        label="Domaine"
        :options="$store.getters.taxonomies.mission_domaines.terms"
        @changed="onFilterChange"
      />
      <query-filter
        type="select"
        name="format"
        :value="query['filter[format]']"
        multiple
        label="Format"
        :options="$store.getters.taxonomies.mission_formats.terms"
        @changed="onFilterChange"
      />
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
          <el-avatar v-else class="bg-primary">
            {{ scope.row.structure.name[0] }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Mission" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.name }}
          </div>
          <div
            v-if="scope.row.structure"
            class="font-light text-gray-600 flex items-center"
          >
            <div class="text-xs">{{ scope.row.structure.name }}</div>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Dates" width="160">
        <template slot-scope="scope">
          <div v-if="scope.row.start_date" class="">
            <span class="text-gray-400 mr-1 text-xs">Du</span>
            {{ scope.row.start_date | formatMedium }}
          </div>
          <div v-if="scope.row.end_date" class="">
            <span class="text-gray-400 mr-1 text-xs">Au</span>
            {{ scope.row.end_date | formatMedium }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Ville" width="185">
        <template slot-scope="scope">
          <div v-if="scope.row.city" class="">
            {{ scope.row.city | cleanCity }}
          </div>
        </template>
      </el-table-column>
      <el-table-column
        v-if="young.regular_latitude && young.regular_longitude"
        label="Distance (kms)"
        width="120"
      >
        <template slot-scope="scope">
          <div v-if="scope.row.latitude && scope.row.longitude">
            {{
              distanceBetweenCoordonates(
                scope.row.latitude,
                scope.row.longitude,
                young.regular_latitude,
                young.regular_longitude
              )
            }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Places" width="90">
        <template slot-scope="scope">
          <div v-if="scope.row.has_places_left">
            {{ scope.row.participations_max - scope.row.youngs_count }}
            {{
              (scope.row.participations_max - scope.row.youngs_count)
                | pluralize(["place", "places"])
            }}
          </div>
          <div v-else>
            Complet
          </div>
          <div class="font-light text-gray-600 text-xs">
            {{ scope.row.youngs_count }} / {{ scope.row.participations_max }}
          </div>
        </template>
      </el-table-column>
      <el-table-column
        v-if="!$store.getters['volet/active']"
        label="Action"
        width="210"
      >
        <template slot-scope="scope">
          <button-assign-mission
            v-if="!scope.row.mission_id"
            :young="young"
            :mission="scope.row"
          />
          <div v-else class="">{{ scope.row.mission.name }}</div>
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
    </div>
    <portal to="volet">
      <young-assignation-volet :young="young" @updated="onUpdatedRow" />
    </portal>
  </div>
</template>

<script>
import { getYoung } from "@/api/young";
import YoungAssignationVolet from "@/layout/components/Volet/YoungAssignationVolet.vue";
import QueryFilter from "@/components/QueryFilter.vue";
import TableWithVolet from "@/mixins/TableWithVolet";
import TableWithFilters from "@/mixins/TableWithFilters";
import { fetchYoungMissions } from "@/api/mission";
import StateTag from "@/components/StateTag";
import ButtonAssignMission from "@/components/ButtonAssignMission";
import YoungInfos from "@/components/infos/YoungInfos";
import { getDistance } from "geolib";

export default {
  name: "YoungAssignation",
  components: {
    StateTag,
    YoungInfos,
    YoungAssignationVolet,
    QueryFilter,
    ButtonAssignMission
  },
  mixins: [TableWithVolet, TableWithFilters],
  props: {
    id: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      loading: true,
      showInfos: false,
      young: {}
    };
  },
  computed: {
    // showInfos() {
    //   return true;
    // }
  },
  created() {
    this.$store.commit("setLoading", true);
    getYoung(this.id)
      .then(response => {
        this.$store.commit("setLoading", false);
        this.young = response.data;
        // Initial filters from young preferences
        if (this.young.preferences) {
          this.$router.push({
            path: this.$router.history.current.path,
            query: {
              ...this.query,
              ["filter[domaines]"]: this.young.preferences.domaine,
              ["filter[format]"]: this.young.preferences.format,
              page: 1
            }
          });
        }
      })
      .catch(error => {
        console.log(error);
        this.$store.commit("setLoading", false);
      });
  },
  beforeRouteUpdate(to, from, next) {
    this.query = { ...to.query };
    this.fetchDatas();
    next();
  },
  methods: {
    fetchRows() {
      return fetchYoungMissions(this.id, this.query);
    },
    distanceBetweenCoordonates(lat1, lng1, lat2, lng2) {
      let distance = getDistance(
        { latitude: lat1, longitude: lng1 },
        { latitude: lat2, longitude: lng2 }
      );
      return Number(distance / 1000)
        .toFixed(1)
        .replace(".", ",");
    }
  }
};
</script>
