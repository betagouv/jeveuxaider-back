<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Organisation</div>
        <div class="flex flex-wrap mb-8">
          <div class="font-bold text-2xl text-gray-800 mr-2">
            {{ structure.name }}
          </div>
          <TagModelState v-if="structure.state" :state="structure.state" />
          <el-tag
            v-if="structure.is_reseau"
            size="medium"
            class="m-1 ml-0"
            type="danger"
          >
            Tête de réseau
          </el-tag>
          <el-tag v-if="structure.reseau_id" class="m-1 ml-0" size="medium">
            {{ structure.reseau_id | reseauFromValue }}
          </el-tag>
        </div>
      </div>
      <div>
        <DropdownStructureButton
          v-if="$store.getters.contextRole == 'admin'"
          :structure="structure"
        />
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/structure/${structure.id}`">
        Informations
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${structure.id}/missions`">
        Missions
        <span class="text-xs text-gray-600"
          >({{ structure.missions_count }})</span
        >
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${structure.id}/history`">
        Historique
      </el-menu-item>
    </el-menu>
    <TableActivities :table-data="tableData" />
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
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithFilters],
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const structure = await $api.getStructure(params.id)
    return {
      structure,
    }
  },
  async fetch() {
    this.query = this.$route.query
    const { data } = await this.$api.fetchActivities({
      'filter[subject_id]': this.$route.params.id,
      'filter[subject_type]': 'Structure',
    })
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleDeleteStructure()
      }
    },
    handleDeleteStructure() {
      if (this.structure.missions_count > 0) {
        this.$alert(
          'Il est impossible de supprimer une organisation qui contient des missions.',
          "Supprimer l'organisation",
          {
            confirmButtonText: 'Retour',
            type: 'warning',
            center: true,
          }
        )
      } else {
        this.$confirm(
          `L'organisation ${this.structure.name} sera définitivement supprimée de la plateforme.<br><br> Voulez-vous continuer ?<br>`,
          "Supprimer l'organisation",
          {
            confirmButtonText: 'Supprimer',
            confirmButtonClass: 'el-button--danger',
            cancelButtonText: 'Annuler',
            center: true,
            dangerouslyUseHTMLString: true,
            type: 'error',
          }
        ).then(() => {
          this.$api.deleteStructure(this.structure.id).then(() => {
            this.$message({
              type: 'success',
              message: `L'organisation ${this.structure.name} a été supprimée.`,
            })
            this.$router.push('/dashboard/structures')
          })
        })
      }
    },
  },
}
</script>

<style scoped lang="sass">
.el-menu--horizontal
  @apply px-12
  > .el-menu-item
    @apply mr-8 p-0 font-medium
    border-bottom: solid 3px #070191
</style>
