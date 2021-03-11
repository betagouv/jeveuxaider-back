<template>
  <el-table
    ref="tableData"
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
              <td class="border px-4 py-2">{{ fieldName(name) }}</td>
              <td class="border px-4 py-2">
                {{ scope.row.properties.old[name] }}
              </td>
              <td class="border px-4 py-2">{{ value }}</td>
            </tr>
          </tbody>
        </table>
      </template>
    </el-table-column>
    <el-table-column prop="updated_at" label="Date" width="190">
      <template slot-scope="scope">
        <div class="text-sm text-gray-900">
          {{ scope.row.updated_at | formatLongWithTime }}
        </div>
      </template>
    </el-table-column>
    <el-table-column prop="type" label="Type" width="120">
      <template slot-scope="scope">
        <div class="text-sm text-gray-900">
          {{ type(scope.row.subject_type) }}
        </div>
      </template>
    </el-table-column>
    <el-table-column prop="subject" label="Objet" min-width="190">
      <template slot-scope="scope">
        <nuxt-link :to="linkSubject(scope.row)">
          <span v-if="scope.row.data.subject_title" class="text-sm">
            {{ scope.row.data.subject_title }} #{{ scope.row.subject_id }}</span
          >
          <span v-else class="text-sm"
            >{{ type(scope.row.subject_type) }} #{{
              scope.row.subject_id
            }}</span
          >
        </nuxt-link>
      </template>
    </el-table-column>
    <el-table-column prop="change" label="Activité" width="350">
      <template slot-scope="scope">
        <div class="text-sm">
          <span v-if="scope.row.description == 'updated'">Modifié par</span>
          <span v-else-if="scope.row.description == 'deleted'">
            Supprimé par
          </span>
          <span v-else-if="scope.row.description == 'created'">Crée par</span>
          <nuxt-link :to="linkCauser(scope.row)">
            {{ scope.row.data.full_name }}
          </nuxt-link>
          <el-tag type="info" size="mini" class="uppercase text-xxs">
            {{ scope.row.data.context_role }}
          </el-tag>
        </div>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'TableActivities',
  props: {
    tableData: {
      type: Array,
      default: null,
    },
  },
  methods: {
    fieldName(name) {
      switch (name) {
        case 'type':
          return 'Type'
        case 'zip':
          return 'Code postal'
        case 'is_visible':
          return 'Visibilité'
        case 'state':
          return 'Statut'
        case 'mobile':
          return 'Téléphone mobile'
        case 'statut_juridique':
          return 'Statut juridique'
        case 'association_types':
          return "Type d'association"
        case 'description':
          return 'Descritpion'
        case 'department':
          return 'Département'
        case 'participations_max':
          return 'Nombre de bénévoles max'
        case 'information':
          return 'Information'
        case 'first_name':
          return 'Prénom'
        case 'last_name':
          return 'Nom'
        case 'disponibilities':
          return 'Disponibilités'
        case 'frequence_granularite':
          return 'Fréquence par'
        case 'frequence':
          return 'Fréquence volume'
        default:
          return name
      }
    },
    type(subjectType) {
      switch (subjectType) {
        case 'App\\Models\\Mission':
          return 'Mission'
        case 'App\\Models\\Structure':
          return 'Organisation'
        case 'App\\Models\\Participation':
          return 'Participation'
        case 'App\\Models\\Profile':
          return 'Utilisateur'
        case 'App\\Models\\Collectivity':
          return 'Collectivité'
        default:
          return 'Autre'
      }
    },
    linkSubject(row) {
      switch (row.subject_type) {
        case 'App\\Models\\Mission':
          return `/dashboard/mission/${row.subject_id}/edit`
        case 'App\\Models\\Structure':
          return `/dashboard/structure/${row.subject_id}`
        case 'App\\Models\\Collectivity':
          return `/dashboard/collectivity/${row.subject_id}`
        case 'App\\Models\\Participation':
          return `/dashboard/participations`
        case 'App\\Models\\Profile':
          return `/dashboard/profile/${row.subject_id}`
        default:
          return '#'
      }
    },
    linkCauser(row) {
      return row.causer_type == 'App\\Models\\User'
        ? `/dashboard/profile/${row.data.causer_id}`
        : '#'
    },
    rowClicked(row) {
      this.$refs.tableData.toggleRowExpansion(row)
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
