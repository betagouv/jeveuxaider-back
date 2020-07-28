<template>
  <el-table
    :data="tableData"
    :highlight-current-row="true"
    @row-click="onClickedRow"
  >
    <el-table-column width="70" align="center">
      <template slot-scope="scope">
        <el-avatar class="bg-primary">
          {{ scope.row.profile.short_name }}
        </el-avatar>
      </template>
    </el-table-column>
    <el-table-column prop="name" label="Bénévole" min-width="320">
      <template slot-scope="scope">
        <template v-if="canShowProfileDetails(scope.row)">
          <div class="text-gray-900">
            {{ scope.row.profile.full_name }}
          </div>
          <div class="font-light text-gray-600">
            <div class="text-xs">
              {{ scope.row.profile.email }}
            </div>
            <div class="text-xs">
              {{ scope.row.profile.mobile }} - {{ scope.row.profile.zip }}
            </div>
          </div>
        </template>
        <template v-else>
          <div class="text-gray-900">
            Anonyme
          </div>
          <div class="font-light text-gray-600 flex items-center">
            <div class="text-xs">
              Coordonnées masquées
            </div>
          </div>
        </template>
      </template>
    </el-table-column>
    <el-table-column prop="name" label="Mission" min-width="320">
      <template slot-scope="scope">
        <div v-if="scope.row.mission" class="text-gray-900">
          <v-clamp :max-lines="1" autoresize
            >#{{ scope.row.mission.id }} - {{ scope.row.mission.name }}</v-clamp
          >
        </div>
        <div
          v-if="scope.row.mission && scope.row.mission.structure"
          class="font-light text-gray-600 flex items-center"
        >
          <div class="text-xs">
            <v-clamp :max-lines="1" autoresize>
              {{ scope.row.mission.structure.name }}
            </v-clamp>
          </div>
        </div>
      </template>
    </el-table-column>
    <el-table-column prop="created_at" label="Crée le" min-width="120">
      <template slot-scope="scope">
        <div class="text-sm text-gray-600">
          {{ scope.row.created_at | fromNow }}
        </div>
      </template>
    </el-table-column>
    <el-table-column prop="state" label="Statut" min-width="250">
      <template slot-scope="scope">
        <participation-dropdown-state
          :form="scope.row"
          @updated="onUpdatedRow"
        ></participation-dropdown-state>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import ParticipationDropdownState from '@/components/ParticipationDropdownState'

export default {
  name: 'TableParticipations',
  components: {
    ParticipationDropdownState,
  },
  props: {
    tableData: {
      type: Array,
      default: null,
    },
    onUpdatedRow: {
      type: Function,
      default: () => {},
    },
    onClickedRow: {
      type: Function,
      default: () => {},
    },
  },
  methods: {
    canShowProfileDetails(row) {
      return row.mission &&
        (row.mission.state != 'Signalée' ||
          this.$store.getters.contextRole !== 'responsable')
        ? true
        : false
    },
  },
}
</script>
