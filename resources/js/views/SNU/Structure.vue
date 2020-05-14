<template>
  <div v-if="!$store.getters.loading" class="structure-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Structure
        </div>
        <div class="flex flex-wrap mb-8">
          <div class="font-bold text-2xl text-gray-800">
            {{ structure.name }}
          </div>
          <state-tag :state="structure.state"></state-tag>
          <el-tag
            v-if="structure.is_reseau"
            size="medium"
            class="m-1 ml-0"
            type="danger"
            >Tête de réseau</el-tag
          >
          <el-tag v-if="structure.reseau_id" class="m-1 ml-0" size="medium">{{
            structure.reseau_id | reseauFromValue
          }}</el-tag>
          <el-tag
            v-if="structure.department"
            type="warning"
            class="m-1 ml-0"
            size="medium"
            >{{ structure.department | fullDepartmentFromValue }}</el-tag
          >
          <el-tooltip
            v-if="structure.ceu"
            class="item"
            effect="dark"
            :content="structure.structure_publique_etat_type"
            placement="top"
          >
            <el-tag size="medium" class="m-1 ml-0" type="info">CEU</el-tag>
          </el-tooltip>
          <el-tag
            v-if="structure.missions_count > 0"
            type="info"
            class="m-1 ml-0"
            size="medium"
            >{{ structure.missions_count }}
            {{
              structure.missions_count | pluralize(["mission", "missions"])
            }}</el-tag
          >
        </div>
      </div>
      <router-link
        :to="{ name: 'StructureFormEdit', params: { id: structure.id } }"
      >
        <el-button type="secondary" icon="el-icon-edit"
          >Modifier la fiche</el-button
        >
      </router-link>
    </div>
    <div class="px-12 mb-12">
      <div class="mb-6 text-2xl">Informations</div>
      <structure-infos :structure="structure"></structure-infos>
    </div>
    <div class="px-12 mb-12">
      <div class="mb-6 text-2xl">Équipe ({{ structure.members.length }})</div>
      <item-description>
        Vous pouver
        <router-link
          :to="{
            name: 'StructureMembers',
            params: {
              id: structure.id
            }
          }"
        >
          <span class="underline cursor-pointer">gérer l'équipe</span>
        </router-link>
        ou
        <router-link
          :to="{
            name: 'StructureMembersAdd',
            params: {
              id: structure.id
            }
          }"
        >
          <span class="underline cursor-pointer"
            >ajouter un membre</span
          > </router-link
        >.
      </item-description>
      <div
        v-for="member in structure.members"
        :key="member.id"
        class="member py-4 px-6"
      >
        <member-teaser :member="member"></member-teaser>
      </div>
    </div>
    <div class="mb-12">
      <div class="px-12 mb-6 text-2xl">
        Missions ({{ structure.missions_count }})
      </div>
      <div class="px-12 mb-3 flex flex-wrap">
        <query-search-filter
          name="search"
          label="Recherche"
          placeholder="Mots clés, etc..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
        <query-search-filter
          name="lieu"
          label="Lieu"
          placeholder="Ville ou code postal"
          :initial-value="query['filter[lieu]']"
          @changed="onFilterChange"
        />
      </div>
      <el-table
        v-loading="loading"
        :data="tableData"
        :highlight-current-row="true"
      >
        <el-table-column width="70" align="center">
          <template>
            <el-avatar
              v-if="structure.logo"
              :src="`${structure.logo}`"
              class="w-10 rounded-full border"
            />
            <el-avatar v-else class="bg-primary">
              {{ structure.name[0] }}
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
              <div class="">{{ scope.row.structure.name }}</div>
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
        <el-table-column label="Places" width="90">
          <template slot-scope="scope">
            <div v-if="scope.row.has_places_left">
              {{ scope.row.participations_max - scope.row.participations_count }}
              {{
                (scope.row.participations_max - scope.row.participations_count)
                  | pluralize(["place", "places"])
              }}
            </div>
            <div v-else>
              Complet
            </div>
            <div class="font-light text-gray-600 text-xs">
              {{ scope.row.participations_count }} / {{ scope.row.participations_max }}
            </div>
          </template>
        </el-table-column>
        <el-table-column prop="state" label="Statut" min-width="170">
          <template slot-scope="scope">
            <state-tag :state="scope.row.state"></state-tag>
          </template>
        </el-table-column>
        <el-table-column
          v-if="
            $store.getters.contextRole !== 'referent' &&
              !$store.getters['volet/active']
          "
          label="Actions"
          width="165"
        >
          <template slot-scope="scope">
            <router-link
              :to="{ name: 'MissionFormEdit', params: { id: scope.row.id } }"
            >
              <el-button icon="el-icon-edit" size="mini" class="m-1">
                Modifier
              </el-button>
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
        >
        </el-pagination>
        <div class="text-secondary text-xs ml-3">
          Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getStructure, fetchStructureMissions } from "@/api/structure";
import StructureInfos from "@/components/infos/StructureInfos";
import TableWithVolet from "@/mixins/TableWithVolet";
import TableWithFilters from "@/mixins/TableWithFilters";
import StateTag from "@/components/StateTag";
import QuerySearchFilter from "@/components/QuerySearchFilter.vue";
import MemberTeaser from "@/components/MemberTeaser";
import ItemDescription from "@/components/forms/ItemDescription";

export default {
  name: "Structure",
  components: {
    StructureInfos,
    StateTag,
    QuerySearchFilter,
    MemberTeaser,
    ItemDescription
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
      loading: false,
      structure: {},
      form: {}
    };
  },
  created() {
    this.$store.commit("setLoading", true);
    getStructure(this.id)
      .then(response => {
        this.$store.commit("setLoading", false);
        this.structure = response.data;
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {
    fetchRows() {
      return fetchStructureMissions(this.id, this.query);
    }
  }
};
</script>
