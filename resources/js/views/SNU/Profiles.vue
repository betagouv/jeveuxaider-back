<template>
  <div class="profiles has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters["user/contextRoleLabel"] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Utilisateurs
        </div>
      </div>
      <div v-if="$store.getters.contextRole === 'admin'" class="">
        <el-dropdown>
          <el-button type="primary">
            <i class="el-icon-plus mr-1"></i> Inviter un utilisateur
          </el-button>
          <el-dropdown-menu type="primary">
            <router-link
              :to="{ name: 'ProfileFormAdd', params: { role: 'superviseur' } }"
            >
              <el-dropdown-item>Superviseur national</el-dropdown-item>
            </router-link>
            <router-link
              :to="{ name: 'ProfileFormAdd', params: { role: 'referent' } }"
            >
              <el-dropdown-item>Référent départemental</el-dropdown-item>
            </router-link>
            <router-link
              :to="{ name: 'ProfileFormAdd', params: { role: 'referent_regional' } }"
            >
              <el-dropdown-item>Référent régional</el-dropdown-item>
            </router-link>
            <router-link
              :to="{ name: 'ProfileFormAdd', params: { role: 'analyste' } }"
            >
              <el-dropdown-item>Datas analyste</el-dropdown-item>
            </router-link>
          </el-dropdown-menu>
        </el-dropdown>
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
          name="role"
          label="Rôle"
          :value="query['filter[role]']"
          :options="rolesList"
          @changed="onFilterChange"
        />
        <query-filter
          v-if="$store.getters.contextRole === 'admin'"
          name="referent_department"
          label="Référent"
          multiple
          :value="query['filter[referent_department]']"
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
            v-if="scope.row.avatar"
            :src="`${scope.row.avatar}`"
            class="w-10 rounded-full border"
          />
          <el-avatar v-else class="bg-primary">
            {{ scope.row.first_name[0] }}{{ scope.row.last_name[0] }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column label="Email" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.first_name }} {{ scope.row.last_name }}
          </div>
          <div class="font-light text-gray-600">{{ scope.row.email }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Roles" min-width="200">
        <template slot-scope="scope">
          <profile-roles-tags :profile="scope.row"></profile-roles-tags>
        </template>
      </el-table-column>

      <el-table-column prop="created_at" label="Crée le" min-width="120">
        <template slot-scope="scope">
          {{ scope.row.created_at | fromNow }}
        </template>
      </el-table-column>
      <el-table-column
        v-if="$store.getters.contextRole === 'admin'"
        label="Actions"
        width="165"
      >
        <template slot-scope="scope">
          <el-dropdown
            v-if="$store.getters.contextRole === 'admin' && scope.row.user_id"
            split-button
            size="mini"
            @command="handleCommand"
          >
            <router-link
              :to="{ name: 'ProfileFormEdit', params: { id: scope.row.id } }"
              ><i class="el-icon-edit mr-1"></i> Modifier
            </router-link>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item
                :command="{ action: 'impersonate', id: scope.row.user_id }"
                class="px-4 py-2"
              >
                <i class="el-icon-s-custom m-1" />Masquarade
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
          <router-link
            v-else
            :to="{ name: 'ProfileFormEdit', params: { id: scope.row.id } }"
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
      <div class="ml-auto">
        <el-button icon="el-icon-download" size="small" @click="onExport"
          >Export</el-button
        >
      </div>
    </div>
    <portal to="volet">
      <profile-volet @updated="onUpdatedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchProfiles, exportProfiles } from "@/api/user";
import TableWithVolet from "@/mixins/TableWithVolet";
import TableWithFilters from "@/mixins/TableWithFilters";
import QueryFilter from "@/components/QueryFilter.vue";
import QueryMainSearchFilter from "@/components/QueryMainSearchFilter.vue";
import ProfileVolet from "@/layout/components/Volet/ProfileVolet.vue";
import ProfileRolesTags from "@/components/ProfileRolesTags.vue";
import fileDownload from "js-file-download";

export default {
  name: "Profiles",
  components: {
    ProfileRolesTags,
    ProfileVolet,
    QueryFilter,
    QueryMainSearchFilter
  },
  mixins: [TableWithVolet, TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
      totalRows: 0
    };
  },
  computed: {
    rolesList(){
      console.log(this.$store.getters.contextRole)
      if(this.$store.getters.contextRole == 'admin' || this.$store.getters.contextRole == 'analyste') {
        return [
            { label: 'Modérateur', value: 'admin' },
            { label: 'Superviseur', value: 'superviseur' },
            { label: 'Analyste', value: 'analyste' },
            { label: 'Référent départemental', value: 'referent' },
            { label: 'Référent régional', value: 'referent_regional' },
            { label: 'Responsable', value: 'responsable' },
            { label: 'Volontaire', value: 'volontaire' },
          ]
      } else {
        return [
            { label: 'Responsable', value: 'responsable' },
            { label: 'Volontaire', value: 'volontaire' },
          ]
      }
    }
  },
  beforeRouteUpdate(to, from, next) {
    this.query = { ...to.query };
    this.fetchDatas();
    next();
  },
  methods: {
    fetchRows() {
      return fetchProfiles(this.query);
    },
    handleCommand(command) {
      if (command.action == "impersonate") {
        this.$store.dispatch("auth/impersonate", command.id);
      }
    },
    onExport() {
      this.loading = true;
      exportProfiles(this.query)
        .then(response => {
          this.loading = false;
          fileDownload(response.data, "utilisateurs.xlsx");
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>
