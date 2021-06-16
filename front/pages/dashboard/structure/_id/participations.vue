<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Organisation</div>
        <div class="flex items-center flex-wrap mb-8">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
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
      <div
        v-if="
          $store.getters.contextRole == 'responsable' &&
          $store.getters.reminders.participations > 0
        "
      >
        <el-button
          type="primary"
          :loading="loadingButton"
          @click="onMassValidation"
        >
          Valider toutes les participations en attente ({{
            $store.getters.reminders.participations
          }})
        </el-button>
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
      <el-menu-item
        :index="`/dashboard/structure/${structure.id}/participations`"
      >
        Participations
        <span class="text-xs text-gray-600"
          >({{ structure.participations_count }})</span
        >
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${structure.id}/history`">
        Historique
      </el-menu-item>
    </el-menu>
    <TableParticipations
      :loading="$fetchState.pending"
      :table-data="tableData"
      :on-updated-row="onUpdatedRow"
      :on-clicked-row="onClickedRow"
    />
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
      <div class="ml-auto">
        <el-button
          :loading="loadingExport"
          icon="el-icon-download"
          size="small"
          @click="onExport"
        >
          Export
        </el-button>
      </div>
    </div>
    <portal to="volet">
      <VoletParticipation @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'
import fileDownload from 'js-file-download'

export default {
  mixins: [TableWithFilters, TableWithVolet],
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const structure = await $api.getStructure(params.id)
    return {
      structure,
    }
  },
  data() {
    return {
      loadingButton: false,
      loadingExport: false,
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchParticipations({
      'filter[mission.structure_id]': this.$route.params.id,
      page: this.$route.query.page || 1,
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
    onExport() {
      this.loadingExport = true
      this.$api
        .exportParticipations(this.query)
        .then((response) => {
          this.loadingExport = false
          fileDownload(response.data, 'participations.xlsx')
        })
        .catch((error) => {
          console.log('exportParticipations', error)
        })
    },
    onMassValidation() {
      this.$confirm(
        'Vous êtes sur le point de valider toutes les participations actuellement en attente de validation (' +
          this.$store.getters.reminders.participations +
          ').<br><br>Êtes-vous sûr de vouloir continuer ?',
        'Validation massive',
        {
          confirmButtonText: 'Oui, je confirme',
          cancelButtonText: 'Annuler',
          dangerouslyUseHTMLString: true,
          // center: true,
          // type: 'warning',
        }
      ).then(() => {
        this.loadingButton = true
        this.$api
          .massValidationParticipation()
          .then(() => {
            this.loadingButton = false
            this.$store.dispatch('reminders')
            this.$message.success({
              message: 'Les participations ont été mises à jour',
            })
            this.$fetch()
          })
          .catch(() => {
            this.loadingButton = false
          })
      })
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
