<template>
  <el-table
    :data="tableData"
    :highlight-current-row="true"
    @row-click="onClickedRow"
  >
    <el-table-column width="70" label="Id" align="center">
      <template slot-scope="scope">
        <div class="text-secondary text-sm">#{{ scope.row.id }}</div>
      </template>
    </el-table-column>
    <el-table-column prop="name" label="Mission" min-width="260">
      <template slot-scope="scope">
        <v-clamp :max-lines="1" autoresize>{{ scope.row.name }}</v-clamp>
        <div v-if="scope.row.structure">
          <div class="text-secondary text-xs">
            {{ scope.row.structure.name }}
          </div>
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Ville">
      <template slot-scope="scope">
        <div v-if="scope.row.city" class>
          {{ scope.row.city | cleanCity }}
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Places" min-width="180">
      <template slot-scope="scope">
        <template v-if="['Annulée', 'Signalée'].includes(scope.row.state)">
          N/A
        </template>
        <template v-else>
          <div class="flex items-center justify-between">
            <div class="leading-snug">
              <div v-if="scope.row.has_places_left" class="flex">
                <div style="width: 90px">
                  {{ scope.row.places_left }}
                  {{ scope.row.places_left | pluralize(['place', 'places']) }}
                </div>
              </div>
              <div v-else>Complet</div>
              <div class="text-secondary text-xs">
                {{ scope.row.participations_count }} /
                {{ scope.row.participations_max }}
              </div>
            </div>
            <router-link
              v-if="scope.row.state == 'Validée'"
              :to="`/dashboard/mission/${scope.row.id}/trouver-des-benevoles`"
            >
              <el-button size="mini" round> Trouver des bénévoles </el-button>
            </router-link>
          </div>
        </template>
      </template>
    </el-table-column>
    <el-table-column
      v-if="!$store.getters['volet/active']"
      label="Crée le"
      min-width="120"
    >
      <template slot-scope="scope">
        <div class="text-sm text-secondary">
          {{ scope.row.created_at | fromNow }}
        </div>
      </template>
    </el-table-column>
    <el-table-column prop="state" label="Statut" min-width="200">
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
