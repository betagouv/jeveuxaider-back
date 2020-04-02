<template>
  <div class="profiles has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">{{ $store.getters["user/contextRoleLabel"] }}</div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Collectivité</div>
      </div>
      <div class>
        <router-link :to="{ name: 'CollectivityFormAdd' }">
          <el-button type="primary" icon="el-icon-plus">Nouvelle collectivité</el-button>
        </router-link>
      </div>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="search"
          placeholder="Rechercher par mots clés..."
          :value="query['filter[search]']"
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table v-loading="loading" :data="tableData" :highlight-current-row="true">
      <el-table-column width="70" align="center">
        <template slot-scope="scope">
          <el-avatar class="bg-primary">
            {{ scope.row.name[0] }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column label="Collectivité" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">{{ scope.row.name }}</div>
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="Crée le" min-width="120">
        <template slot-scope="scope">{{ scope.row.created_at | fromNow }}</template>
      </el-table-column>
      <el-table-column label="Actions" width="165">
        <template slot-scope="scope">
          <router-link :to="{ name: 'CollectivityFormEdit', params: { id: scope.row.id } }">
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
    </div>
  </div>
</template>

<script>
import { fetchCollectivities } from "@/api/collectivity";
import TableWithFilters from "@/mixins/TableWithFilters";
import QueryMainSearchFilter from "@/components/QueryMainSearchFilter.vue";

export default {
  name: "Collectivities",
  components: {
    QueryMainSearchFilter
  },
  mixins: [TableWithFilters],
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
      return fetchCollectivities(this.query);
    }
  }
};
</script>
