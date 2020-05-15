<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">{{ $store.getters["user/contextRoleLabel"] }}</div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Contenus - Modèles de mission</div>
      </div>
      <div class>
        <new-content-dropdown></new-content-dropdown>
      </div>
    </div>
    <div class="px-12 mb-12">
      <contents-menu index="/dashboard/contents/mission-templates"></contents-menu>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="search"
          placeholder="Rechercher par mots clés..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table v-loading="loading" :data="tableData" :highlight-current-row="true">
      <el-table-column label="#" min-width="70" align="center">
        <template slot-scope="scope">
          <div>{{ scope.row.id }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Nom du modèle" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">{{ scope.row.title }}</div>
          <div class="text-gray-900">@TODO: prioritaire ?</div>
        </template>
      </el-table-column>
      <el-table-column label="Thématique" min-width="320">
        <template slot-scope="scope">
          <div v-if="scope.row.thematique" class="text-gray-900">{{ scope.row.thematique.name }}</div>
        </template>
      </el-table-column>
      <el-table-column prop="updated_at" label="Modifiée le" min-width="120">
        <template slot-scope="scope">{{ scope.row.updated_at | fromNow }}</template>
      </el-table-column>
      <el-table-column label="Actions" width="165">
        <template slot-scope="scope">
          <el-dropdown
            size="small"
            split-button
            trigger="click"
            @click="handleClickEdit(scope.row.id)"
            @command="handleCommand"
          >
            <i class="el-icon-edit mr-2"></i>Modifier
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item :command="{ action: 'delete', id: scope.row.id }">Supprimer</el-dropdown-item>
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
      ></el-pagination>
      <div
        class="text-secondary text-xs ml-3"
      >Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats</div>
    </div>
  </div>
</template>

<script>
import { fetchMissionTemplates, deleteMissionTemplate } from "@/api/app";
import TableWithFilters from "@/mixins/TableWithFilters";
import QueryMainSearchFilter from "@/components/QueryMainSearchFilter.vue";
import ContentsMenu from "@/components/ContentsMenu";
import NewContentDropdown from "@/components/NewContentDropdown";

export default {
  name: "MissionTemplates",
  components: {
    QueryMainSearchFilter,
    ContentsMenu,
    NewContentDropdown
  },
  mixins: [TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
    };
  },
  methods: {
    fetchRows() {
      return fetchMissionTemplates(this.query)
    },
    handleCommand(command) {
      if (command.action == "delete") {
        this.handleClickDelete(command.id);
      } else {
        this.$router.push(command);
      }
    },
    handleClickEdit(id) {
        this.$router.push({
          name: `ModelFormEdit`,
          params: { id: id }
        });
    },
    handleClickDelete(id) {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer ce modèle ?`,
        "Supprimer ce modèle",
        {
          confirmButtonText: "Supprimer",
          confirmButtonClass: "el-button--danger",
          cancelButtonText: "Annuler",
          center: true,
          type: "error"
        }
      ).then(() => {
         deleteMissionTemplate( id).then(() => {
            this.$message({
              type: "success",
              message: `Le modèle a été supprimé.`
            });
            this.fetchDatas();
          });
      });
    }
  }
};
</script>