<template>
  <el-table
    v-loading="loading"
    :data="tableData"
    :highlight-current-row="true"
    @row-click="onClickedRow"
  >
    <el-table-column width="70" label="Id" align="center">
      <template slot-scope="scope">
        <div class="text-secondary text-sm">{{ scope.row.id }}</div>
      </template>
    </el-table-column>
    <el-table-column prop="name" label="Mission" min-width="300">
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
          <v-clamp :max-lines="1" autoresize>{{
            scope.row.city | cleanCity
          }}</v-clamp>
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Places" width="280">
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
              v-if="
                scope.row.state == 'Validée' &&
                scope.row.has_places_left &&
                $store.getters.contextRole == 'responsable'
              "
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
      label="Créée le"
      width="120"
    >
      <template slot-scope="scope">
        <div class="text-sm text-secondary">
          {{ scope.row.created_at | fromNow }}
        </div>
      </template>
    </el-table-column>
    <el-table-column prop="state" label="Statut" width="250">
      <template slot-scope="scope">
        <DropdownMissionState :mission="scope.row" @updated="onUpdatedRow" />
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  props: {
    tableData: {
      type: Array,
      default: null,
    },
    loading: {
      type: Boolean,
      default: false,
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
}
</script>

<style lang="sass" scoped>
::v-deep
  a
    @apply text-primary font-medium
  .text-xxs
    font-size: 10px !important
</style>
