<template>
  <div>
    <div class="uppercase text-secondary text-sm font-semibold mb-2 mt-8">
      Activité
    </div>
    <el-table v-loading="loading" :data="tableData">
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
      <el-table-column prop="change">
        <template slot-scope="scope">
          <div class="text-xs text-secondary">
            {{ scope.row.updated_at | formatMediumWithTime }}
          </div>
          <div class="text-xs -mt-1 text-gray-900">
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
  </div>
</template>

<script>
import { fetchActivities } from '@/api/app'
import ActivityUtils from '@/mixins/ActivityUtils'

export default {
  name: 'TableActivities',
  mixins: [ActivityUtils],
  props: {
    subjectType: {
      type: String,
      default: null,
    },
    subjectId: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      loading: true,
      tableData: [],
    }
  },
  watch: {
    subjectId: {
      handler() {
        this.fetchActivities()
      },
      immediate: true,
    },
  },
  methods: {
    async fetchActivities() {
      const query = {
        'filter[subject_id]': this.subjectId,
        'filter[subject_type]': this.subjectType,
      }
      const { data } = await fetchActivities(query) // this.query
      this.tableData = data.data
      this.loading = false
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep
  table thead
    display: none
  .el-table td
    padding: 6px 0px
</style>
