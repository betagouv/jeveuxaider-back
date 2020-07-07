<template>
  <el-table
    :data="tableData"
    :highlight-current-row="true"
    @row-click="onClickedRow"
  >
    <el-table-column width="70" align="center">
      <template slot-scope="scope">
        <el-avatar v-if="scope.row.structure" class="bg-primary">
          {{ scope.row.structure.name[0] }}
        </el-avatar>
      </template>
    </el-table-column>
    <el-table-column prop="name" label="Mission" min-width="300">
      <template slot-scope="scope">
        <div class="text-gray-900">
          <v-clamp :max-lines="2" autoresize>{{ scope.row.name }}</v-clamp>
        </div>
        <div
          v-if="scope.row.structure"
          class="font-light text-gray-600 flex items-center"
        >
          <div class="text-xs">{{ scope.row.structure.name }}</div>
        </div>
      </template>
    </el-table-column>
    <!-- <el-table-column label="Dates" width="160">
        <template slot-scope="scope">
          <div v-if="scope.row.start_date" class>
            <span class="text-gray-400 mr-1 text-xs">Du</span>
            {{ scope.row.start_date | formatMedium }}
          </div>
          <div v-if="scope.row.end_date" class>
            <span class="text-gray-400 mr-1 text-xs">Au</span>
            {{ scope.row.end_date | formatMedium }}
          </div>
        </template>
      </el-table-column> -->
    <el-table-column label="Ville" min-width="185">
      <template slot-scope="scope">
        <div v-if="scope.row.city" class>
          {{ scope.row.city | cleanCity }}
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Places" min-width="150">
      <template slot-scope="scope">
        <template v-if="['Annulée', 'Signalée'].includes(scope.row.state)">
          N/A
        </template>
        <template v-else>
          <div v-if="scope.row.has_places_left">
            {{ scope.row.participations_max - scope.row.participations_count }}
            {{
              (scope.row.participations_max - scope.row.participations_count)
                | pluralize(['place', 'places'])
            }}
          </div>
          <div v-else>
            Complet
          </div>
          <div class="font-light text-gray-600 text-xs">
            {{ scope.row.participations_count }} /
            {{ scope.row.participations_max }}
          </div>
        </template>
      </template>
    </el-table-column>
    <el-table-column prop="state" label="Statut" min-width="250">
      <template slot-scope="scope">
        <mission-dropdown-state
          :form="scope.row"
          @updated="onUpdatedRow"
        ></mission-dropdown-state>
      </template>
    </el-table-column>
    <el-table-column
      v-if="!$store.getters['volet/active']"
      label="Actions"
      width="250"
    >
      <template slot-scope="scope">
        <el-dropdown
          size="small"
          split-button
          trigger="click"
          @command="handleCommand"
        >
          Choisissez une action
          <el-dropdown-menu slot="dropdown">
            <router-link
              :to="{
                name: 'Mission',
                params: { id: scope.row.id },
              }"
              target="_blank"
            >
              <el-dropdown-item command=""
                >Visualiser la mission</el-dropdown-item
              >
            </router-link>
            <router-link
              :to="{
                name: 'MissionFormEdit',
                params: { id: scope.row.id },
              }"
            >
              <el-dropdown-item command=""
                >Modifier la mission</el-dropdown-item
              >
            </router-link>
            <el-dropdown-item
              v-if="canClone()"
              :command="{ action: 'clone', id: scope.row.id }"
            >
              Dupliquer la mission
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import MissionDropdownState from '@/components/MissionDropdownState'
import { cloneMission } from '@/api/mission'

export default {
  name: 'TableMissions',
  components: {
    MissionDropdownState,
  },
  props: {
    tableData: {
      type: Array,
      default: null,
    },
    onUpdatedRow: {
      type: Function,
      default: null,
    },
    onClickedRow: {
      type: Function,
      default: () => {},
    },
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'clone') {
        this.clone(command.id)
      } else {
        this.$router.push(command)
      }
    },
    canClone() {
      let roles = ['admin', 'referent', 'responsable']
      return roles.includes(this.$store.getters.contextRole)
    },
    clone(id) {
      this.loading = true
      cloneMission(id).then((response) => {
        this.$router
          .push({
            path: `/dashboard/mission/${response.data.id}/edit`,
          })
          .then(() => {
            this.$message({
              message: 'La mission a été dupliquée !',
              type: 'success',
            })
          })
      })
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
