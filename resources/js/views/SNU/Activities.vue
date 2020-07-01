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

    <el-table
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
    >
      <el-table-column type="expand">
        <template slot-scope="scope">
          <table
            v-if="event(scope.row) == 'updated'"
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
      <el-table-column prop="updated_at" label="Date" width="220">
        <template slot-scope="scope">
          <div class="text-sm text-gray-900">
            {{ scope.row.updated_at | formatLongWithTime }}
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="subject" label="Objet" width="150">
        <template slot-scope="scope">
          <router-link :to="linkSubject(scope.row)">
            <el-tag type="info" size="mini" class="mt-1">
              {{ type(scope.row.subject_type) }} #{{ scope.row.subject_id }}
            </el-tag>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column prop="change" label="Activité">
        <template slot-scope="scope">
          <div class="text-sm">
            <span v-if="event(scope.row) == 'updated'">modifié par</span>
            <span v-else-if="event(scope.row) == 'deleted'">supprimé par</span>
            <span v-else-if="event(scope.row) == 'created'">crée par</span>
            <router-link :to="linkCauser(scope.row)">
              {{ causer(scope.row) }}
            </router-link>
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

export default {
  name: 'Activities',
  mixins: [TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
    }
  },
  methods: {
    fetchRows() {
      return fetchActivities(this.query)
    },
    type(subject_type) {
      return subject_type == 'App\\Models\\Mission'
        ? 'Mission'
        : subject_type == 'App\\Models\\Structure'
        ? 'Structure'
        : 'Autre'
    },
    linkSubject(row) {
      return row.subject_type == 'App\\Models\\Mission'
        ? `/dashboard/mission/${row.subject_id}/edit`
        : row.subject_type == 'App\\Models\\Structure'
        ? `/dashboard/structure/${row.subject_id}`
        : '#'
    },
    linkCauser(row) {
      return row.causer_type == 'App\\Models\\User'
        ? `/dashboard/profile/${this.causerId(row)}`
        : '#'
    },
    event(row) {
      return row.description.split('|')[0]
    },
    causer(row) {
      return row.description.split('|')[1]
    },
    causerId(row) {
      return row.description.split('|')[2]
    },
    subject(row) {
      return row.description.split('|')[3]
    },
  },
}
</script>

<style lang="sass" scoped>

::v-deep
  a
    @apply text-primary font-medium
    .el-tag
      @apply font-normal
</style>
