<template>
  <el-card class="mb-5 p-5" shadow="never">
    <template v-if="data">
      <div class="w-full">
        <el-table :data="data" style="width: 100%;" @row-click="onClickedRow">
          <el-table-column prop="key" label="#" width="50">
            <template slot-scope="scope">
              <span>{{ scope.row.key }}</span>
            </template>
          </el-table-column>
          <el-table-column prop="label" label="Département">
            <template slot-scope="scope">
              <span class="text-gray-500">{{ scope.row.name }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="structures_count"
            label="Orga."
            width="100"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.structures_count | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="missions_count"
            label="Miss."
            width="90"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.missions_count | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="participations_count"
            label="Partic."
            width="100"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.participations_count | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="volontaires_count"
            label="Volon."
            width="100"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.volontaires_count | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="service_civique_count"
            label="Volon. SC"
            width="120"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.service_civique_count | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="missions_available"
            label="Miss. dispos."
            width="140"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.missions_available | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="places_available"
            label="Places dispos."
            width="200"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <el-tag :type="type(scope.row.places_available)">
                <template v-if="scope.row.places_available > 0">
                  {{ scope.row.places_available | formatNumber }}
                  {{
                    scope.row.places_available | pluralize(['place', 'places'])
                  }}
                </template>
                <template v-else>
                  Aucune place
                </template>
              </el-tag>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </template>
    <template v-else>
      <i class="el-icon-loading" />
    </template>
  </el-card>
</template>

<script>
import { statistics } from '../api/app'
export default {
  props: {
    label: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    link: {
      type: String,
      required: false,
      default: null,
    },
  },
  data() {
    return {
      data: null,
    }
  },
  created() {
    statistics(this.name).then((response) => {
      this.data = response.data
    })
  },
  methods: {
    type(places) {
      if (places < 10) {
        return 'danger'
      } else if (places < 500) {
        return 'warning'
      } else {
        return 'info'
      }
    },
    onClickedRow(row) {
      this.$router.push(`/dashboard/missions?filter[department]=${row.key}`)
    },
  },
}
</script>
