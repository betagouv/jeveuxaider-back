<template>
  <div>
    <div class="header flex justify-between">
      <h1 class="text-3xl mb-5">Collaborateurs</h1>
      <div class="actions">
        <router-link  :to="{ name: 'invite.collaborators' }">
          <el-button type="primary">
            <i class="el-icon-plus mr-1 font-bold"></i> Inviter un collaborateur
          </el-button>
        </router-link>
      </div>
    </div>
    <div class="mb-5">
      {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
    </div>
    <el-table
      v-loading="loading"
      :data="tableData"
    >
      <el-table-column width="100" align="center">
        <template slot-scope="scope">
          <el-avatar class="bg-primary">
            {{ scope.row.short_name }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column label="Email">
        <template slot-scope="scope">
          <div class="flex flex-row items-center">
          <div class="text-gray-900">
            {{ scope.row.first_name }} {{ scope.row.last_name }}
          </div>
          <el-tooltip
            v-if="!scope.row.registered"
            class="item"
            effect="dark"
            content="Ce profil n'a pas créé son compte"
            placement="top"
          >
            <el-tag type="info" size="small" class="m-1">
              <i class="el-icon-info"></i> Invité
            </el-tag>
          </el-tooltip>
          </div>
          <div class="font-light text-gray-600 text-sm">{{ scope.row.email }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Crée le">
        <template slot-scope="scope">
          <div class="font-light">{{ scope.row.created_at | formatLong }}</div>
        </template>
      </el-table-column>
    </el-table>
    <div class="my-5 flex justify-end">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="totalRows"
        :page-size="10"
        :current-page="Number(query.page)"
        @current-change="onPageChange"
      >
      </el-pagination>
    </div>
  </div>
</template>

<script type="text/babel">
import TableWithFilters from "@/mixins/TableWithFilters";
import { fetchProfiles } from "@/api/user"

export default {
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
  created() {
    this.tableData = this.fetchDatas(1);
  },
  methods: {
    fetchRows() {
      return fetchProfiles(this.query);
    },
    onExport() {
      console.log('EXPORT')
    }
  }
};
</script>
