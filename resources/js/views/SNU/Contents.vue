<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters["user/contextRoleLabel"] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Contenus
        </div>
      </div>
      <div class>
        <el-dropdown>
          <el-button type="primary">
            <i class="el-icon-plus mr-1" /> Ajouter un contenu
          </el-button>
          <el-dropdown-menu type="primary">
            <router-link :to="{ name: 'FaqFormAdd' }">
              <el-dropdown-item>Nouvelle question</el-dropdown-item>
            </router-link>
            <router-link :to="{ name: 'ReleaseFormAdd' }">
              <el-dropdown-item>Nouvelle release</el-dropdown-item>
            </router-link>
            <router-link :to="{ name: 'PageFormAdd' }">
              <el-dropdown-item>Nouvelle page</el-dropdown-item>
            </router-link>
            <router-link :to="{ name: 'CollectivityFormAdd' }">
              <el-dropdown-item>Nouvelle collectivité</el-dropdown-item>
            </router-link>
            <router-link :to="{ name: 'DocumentFormAdd' }">
              <el-dropdown-item>Nouveau document</el-dropdown-item>
            </router-link>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </div>
    <div class="px-12 mb-6 -mt-6">
      <el-radio-group
        v-model="type"
        @change="handleChangeType"
      >
        <el-radio-button label="Faqs" />
        <el-radio-button label="Releases" />
        <el-radio-button label="Pages" />
        <el-radio-button label="Collectivités" />
        <el-radio-button label="Documents" />
      </el-radio-group>
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
    <el-table
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
    >
      <el-table-column
        v-if="type == 'Faqs'"
        label="Ordre"
        min-width="70"
        align="center"
      >
        <template slot-scope="scope">
          <el-avatar class="bg-primary">
            {{ scope.row.weight }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column
        v-else
        label="ID"
        min-width="70"
        align="center"
      >
        <template slot-scope="scope">
          <el-avatar class="bg-primary">
            {{ scope.row.id }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column
        label="Titre"
        min-width="320"
      >
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.title }}
          </div>
          <div
            v-if="type == 'Collectivités'"
            class="font-light text-gray-600 text-xs"
          >
            <router-link
              :to="{ name: 'CollectivitySlug', params: { slug: scope.row.slug } }"
              target="_blank"
            >
              /territoires/{{ scope.row.slug }}
            </router-link>
          </div>
        </template>
      </el-table-column>
      <el-table-column
        prop="created_at"
        label="Crée le"
        min-width="120"
      >
        <template slot-scope="scope">
          {{ scope.row.created_at | fromNow }}
        </template>
      </el-table-column>
      <el-table-column
        label="Actions"
        width="165"
      >
        <template slot-scope="scope">
          <el-dropdown
            size="small"
            split-button
            trigger="click"
            @click="handleClickEdit(scope.row.id)"
            @command="handleCommand"
          >
            <i class="el-icon-edit mr-2" />Modifier
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item :command="{ action: 'delete', id: scope.row.id }">
                Supprimer
              </el-dropdown-item>
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
      <div
        class="text-secondary text-xs ml-3"
      >
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>
  </div>
</template>

<script>
import { fetchReleases, deleteRelease } from "@/api/app";
import { fetchFaqs, deleteFaq } from "@/api/app";
import { fetchPages, deletePage } from "@/api/app";
import { fetchCollectivities, deleteCollectivity } from "@/api/app";
import { fetchDocuments, deleteDocument } from "@/api/app";
import TableWithFilters from "@/mixins/TableWithFilters";
import QueryMainSearchFilter from "@/components/QueryMainSearchFilter.vue";

export default {
  name: "Contents",
  components: {
    QueryMainSearchFilter
  },
  mixins: [TableWithFilters],
  data() {
    return {
      loading: true,
      type: "Faqs",
      tableData: [],
      totalRows: 0
    };
  },
  created() {
    this.type = this.$route.query.type ? this.$route.query.type : "Faqs";
    this.query = { ...this.$router.history.current.query };
    this.tableData = this.fetchDatas();
    this.showFilters = this.activeFilters > 0 ? true : false;
  },
  methods: {
    handleChangeType() {
      this.query.type = this.type;
      this.query.page = 1;
      this.fetchDatas();
    },
    fetchRows() {
      if (this.type == "Faqs") {
        return fetchFaqs(this.query);
      } else if (this.type == "Releases") {
        return fetchReleases(this.query);
      } else if (this.type == "Collectivités") {
        return fetchCollectivities(this.query);
      } else if (this.type == "Documents") {
        return fetchDocuments(this.query);
      } else {
        return fetchPages(this.query);
      }
    },
    handleCommand(command) {
      if (command.action == "delete") {
        this.handleClickDelete(command.id);
      } else {
        this.$router.push(command);
      }
    },
    handleClickEdit(id) {
      if (this.type == "Faqs") {
        this.$router.push({
          name: "FaqFormEdit",
          params: { id: id }
        });
      } else if (this.type == "Releases") {
        this.$router.push({
          name: "ReleaseFormEdit",
          params: { id: id }
        });
      } else if (this.type == "Collectivités") {
        this.$router.push({
          name: "CollectivityFormEdit",
          params: { id: id }
        });
      } else if (this.type == "Documents") {
        this.$router.push({
          name: "DocumentFormEdit",
          params: { id: id }
        });
      } else {
        this.$router.push({
          name: "PageFormEdit",
          params: { id: id }
        });
      }
    },
    handleClickDelete(id) {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer cet item ?`,
        "Supprimer cet item",
        {
          confirmButtonText: "Supprimer",
          confirmButtonClass: "el-button--danger",
          cancelButtonText: "Annuler",
          center: true,
          type: "error"
        }
      ).then(() => {
        if (this.type == "Faqs") {
          deleteFaq(id).then(() => {
            this.$message({
              type: "success",
              message: `La question a été supprimée.`
            });
            this.fetchDatas();
          });
        } else if (this.type == "Releases") {
          deleteRelease(id).then(() => {
            this.$message({
              type: "success",
              message: `La release a été supprimée.`
            });
            this.fetchDatas();
          });
        } else if (this.type == "Collectivités") {
          deleteCollectivity(id).then(() => {
            this.$message({
              type: "success",
              message: `La collectivité a été supprimée.`
            });
            this.fetchDatas();
          });
        } else if (this.type == "Documents") {
          deleteDocument(id).then(() => {
            this.$message({
              type: "success",
              message: `Le document a été supprimée.`
            });
            this.fetchDatas();
          });
        } else {
          deletePage(id).then(() => {
            this.$message({
              type: "success",
              message: `La page a été supprimée.`
            });
            this.fetchDatas();
          });
        }
      });
    }
  }
};
</script>
