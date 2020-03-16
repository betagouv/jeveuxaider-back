<template>
  <div>
    <div class="header flex justify-between">
      <h1 class="text-3xl mb-5">Activités</h1>
      <div class="actions">
        <router-link :to="{ name: 'activity.create' }">
          <el-button type="primary">
            <i class="el-icon-plus mr-1 font-bold"></i> Ajouter une activité
          </el-button>
        </router-link>
      </div>
    </div>
    <div class="mb-5">
      {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
    </div>
    <el-table
      v-loading="loading"
      class="cliquable"
      :data="tableData"
      @row-click="handleRowClick"
    >
      <el-table-column width="60" label="#" align="center">
        <template slot-scope="scope">
            {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column label="Activités" width="500">
        <template slot-scope="scope">
          <div class="">{{ scope.row.libelle }}</div>
          <div class="font-light text-gray-600 text-sm">
            <span v-if="scope.row.type_activite">{{ scope.row.type_activite }}</span> <span v-if="scope.row.nature_activite">- {{ scope.row.nature_activite }}</span>
          </div>
          <div v-if="scope.row.debut_projet_annee" class="font-light text-gray-600 text-sm">
            {{ scope.row.debut_projet_annee }}-{{ scope.row.debut_projet_trimestre }} au {{ scope.row.fin_projet_annee }}-{{ scope.row.debut_projet_trimestre }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Enjeu" align="center">
        <template slot-scope="scope">
          <div class="">{{ scope.row.niveau_enjeu }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Faisabilité" align="center">
        <template slot-scope="scope">
          <div class="">{{ scope.row.niveau_faisabilite }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Budget">
        <template slot-scope="scope">
          <div class="">{{ scope.row.total_budgets | formatNumber }}€</div>
          <div class="font-light text-gray-600 text-sm">{{ scope.row.total_charges_amoa + scope.row.total_charges_moe | formatNumber }}JH</div>
        </template>
      </el-table-column>
      <el-table-column label="Statut">
        <template slot-scope="scope">
          <activity-status-tag v-if="scope.row.statut" :status="scope.row.statut" ></activity-status-tag>
        </template>
      </el-table-column>
      <el-table-column label="Actions">
        <template slot-scope="scope">
          <el-dropdown size="medium" split-button>
            <router-link :to="{ name: 'activity.edit', params: { id: scope.row.id }}">Modifier</router-link>
            <el-dropdown-menu slot="dropdown">
              <router-link :to="{ name: 'activity.budget.create', params: { id: scope.row.id } , query: { type: 'Budget' }}">
                <el-dropdown-item>Ajouter un budget</el-dropdown-item>
              </router-link>
              <router-link :to="{ name: 'activity.budget.create', params: { id: scope.row.id } , query: { type: 'Charge interne AMOA' }}">
                <el-dropdown-item>Ajouter une charge AMOA</el-dropdown-item>
              </router-link>
              <router-link :to="{ name: 'activity.budget.create', params: { id: scope.row.id } , query: { type: 'Charge interne MOE' }}">
                <el-dropdown-item>Ajouter une charge MOE</el-dropdown-item>
              </router-link>
            </el-dropdown-menu>
          </el-dropdown>
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
import TableWithFilters from "@/mixins/TableWithFilters"
import { fetchActivities } from "@/api/activity"
import ActivityStatusTag from "@/components/ActivityStatusTag.vue"

export default {
  components: {
    ActivityStatusTag
  },
  mixins: [TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
      totalRows: 0
    };
  },
  created() {
    this.$store.dispatch("")
  },
  beforeRouteUpdate(to, from, next) {
    this.query = { ...to.query };
    this.fetchDatas();
    next();
  },
  created() {
    this.tableData = this.fetchDatas();
  },
  methods: {
    fetchRows() {
      return fetchActivities(this.query);
    },
    handleRowClick(row, column){
      if (column.label != "Actions") {
        this.$router.push({ name: 'activity.view', params: { id: row.id }})
      }
    },
    onExport() {
      console.log('EXPORT')
    }
  }
};
</script>
