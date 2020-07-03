<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Activité</div>
      </div>
    </div>

    <div class="flex flex-wrap px-12 mb-3">
      <query-search-filter
        name="subject_id"
        label="ID de l'objet"
        placeholder="ex: 1494"
        :initial-value="query['filter[subject_id]']"
        @changed="onFilterChange"
      />
      <query-filter
        type="select"
        name="subject_type"
        :value="query['filter[subject_type]']"
        label="Type de l'objet"
        :options="subjectTypes"
        @changed="onFilterChange"
      />
    </div>

    <el-table
      ref="tableData"
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
      @row-click="rowClicked"
    >
      <el-table-column type="expand">
        <template slot-scope="scope">
          <table
            v-if="scope.row.description == 'updated'"
            class="table-auto text-secondary text-sm"
          >
            <thead>
              <tr>
                <th class="px-4 py-2 border-t-0"></th>
                <th class="px-4 py-2 border-t-0">Avant</th>
                <th class="px-4 py-2 border-t-0">Après</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(value, name) in scope.row.properties.attributes"
                :key="name"
              >
                <td class="border px-4 py-2">{{ name }}</td>
                <td class="border px-4 py-2">
                  {{ scope.row.properties.old[name] }}
                </td>
                <td class="border px-4 py-2">{{ value }}</td>
              </tr>
            </tbody>
          </table>
        </template>
      </el-table-column>
      <el-table-column prop="updated_at" label="Date" width="170">
        <template slot-scope="scope">
          <div class="text-sm text-gray-900">
            {{ scope.row.updated_at | formatLongWithTime }}
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="subject" label="Objet" width="190">
        <template slot-scope="scope">
          <router-link :to="linkSubject(scope.row)">
            <span class="text-sm"
              >{{ type(scope.row.subject_type) }} #{{
                scope.row.subject_id
              }}</span
            >
          </router-link>
        </template>
      </el-table-column>
      <el-table-column prop="change" label="Activité">
        <template slot-scope="scope">
          <div class="text-sm">
            <span v-if="scope.row.description == 'updated'">Modifié par</span>
            <span v-else-if="scope.row.description == 'deleted'">
              Supprimé par
            </span>
            <span v-else-if="scope.row.description == 'created'">Crée par</span>
            <router-link :to="linkCauser(scope.row)">
              {{ scope.row.data.full_name }}
            </router-link>
            <el-tag type="info" size="mini" class="uppercase text-xxs">
              {{ scope.row.data.context_role }}
            </el-tag>
          </div>
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
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>
  </div>
</template>

<script>
import { fetchActivities } from '@/api/app'
import TableWithFilters from '@/mixins/TableWithFilters'
import ActivityUtils from '../../mixins/ActivityUtils.vue'
import QueryFilter from '@/components/QueryFilter.vue'
import QuerySearchFilter from '@/components/QuerySearchFilter.vue'

export default {
  name: 'Activities',
  components: {
    QueryFilter,
    QuerySearchFilter,
  },
  mixins: [TableWithFilters, ActivityUtils],
  data() {
    return {
      loading: true,
      tableData: [],
      subjectTypes: [
        { label: 'Mission', value: 'Mission' },
        { label: 'Structure', value: 'Structure' },
        { label: 'Participation', value: 'Participation' },
        { label: 'Utilisateur', value: 'Profile' },
      ],
    }
  },
  methods: {
    fetchRows() {
      return fetchActivities(this.query)
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep
  a
    @apply text-primary font-medium
  .text-xxs
    font-size: 10px !important
</style>
