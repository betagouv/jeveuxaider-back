<template>
  <div class="">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Mission</div>
        <div class="flex flex-wrap mb-8 max-w-3xl">
          <div class="font-bold text-2xl text-gray-800 mr-2">
            {{ mission.name }}
          </div>
          <TagModelState :state="mission.state" />
        </div>
      </div>
      <div>
        <DropdownMissionButton :mission="mission" />
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/mission/${mission.id}`">
        Informations
      </el-menu-item>
      <el-menu-item
        v-if="mission"
        :index="`/dashboard/mission/${mission.id}/participations`"
      >
        Participations
        <span class="text-xs text-gray-600">
          ({{ mission.participations_total }})
        </span>
      </el-menu-item>
      <el-menu-item :index="`/dashboard/mission/${mission.id}/history`">
        Historique
      </el-menu-item>
    </el-menu>

    <div class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl">Informations</div>
          </div>
          <ModelMissionInfos :mission="mission" />
        </el-card>
        <div>
          <el-card shadow="never" class="p-4 mb-4">
            <div class="flex justify-between">
              <div class="mb-6 text-xl">Responsable</div>
            </div>
            <ModelMemberTeaser
              class="member py-2"
              :member="mission.responsable"
            />
          </el-card>
          <el-card shadow="never" class="p-4">
            <div class="flex justify-between">
              <nuxt-link
                :to="`/dashboard/structure/${structure.id}`"
                class="mb-6 text-xl hover:text-blue-800"
                >{{ structure.name }}</nuxt-link
              >
            </div>
            <ModelStructureInfos :structure="structure" />
          </el-card>
        </div>
      </div>
    </div>
    <!-- <div v-else-if="tab == 'history'">
      <TableActivities :table-data="tableData" />
    </div>
    <div v-else-if="tab == 'participations'">
      <TableParticipations
        :table-data="tableData"
        :on-updated-row="onUpdatedRow"
        :on-clicked-row="onClickedRow"
      />
    </div>
    <div v-if="tab" class="m-3 flex items-center">
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
    <portal to="volet">
      <participation-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal> -->
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      ![
        'admin',
        'referent',
        'referent_regional',
        'superviseur',
        'responsable',
      ].includes(store.getters.contextRole)
    ) {
      return error({ statusCode: 403 })
    }
    const mission = await $api.getMission(params.id)

    if (store.getters.contextRole == 'responsable') {
      if (store.getters.structure.id != mission.structure_id) {
        return error({ statusCode: 403 })
      }
    }

    const structure = await $api.getStructure(mission.structure.id)
    return {
      structure,
      mission,
    }
  },
  data() {
    return {
      loading: true,
      mission: null,
      structure: null,
      tableData: [],
    }
  },
  methods: {
    // async fetchRows() {
    //   const response = await getMission(this.id)
    //   this.mission = response.data
    //   const responseStructure = await getMissionStructure(this.mission.id)
    //   this.structure = responseStructure.data
    //   if (this.tab == 'history') {
    //     return fetchActivities({
    //       'filter[subject_id]': this.id,
    //       'filter[subject_type]': 'Mission',
    //       page: this.$route.query.page || 1,
    //     })
    //   }
    //   if (this.tab == 'participations') {
    //     return fetchParticipations({
    //       'filter[mission.id]': this.id,
    //       page: this.$route.query.page || 1,
    //     })
    //   }
    // },
    // handleCommand(command) {
    //   if (command.action == 'delete') {
    //     this.handleDelete()
    //   } else if (command.action == 'clone') {
    //     this.hanldleClone()
    //   } else {
    //     this.$router.push(command)
    //   }
    // },
    // hanldleClone() {
    //   this.loading = true
    //   cloneMission(this.mission.id).then((response) => {
    //     this.$router
    //       .push({
    //         path: `/dashboard/mission/${response.data.id}/edit`,
    //       })
    //       .then(() => {
    //         this.$message({
    //           message: 'La mission a été dupliquée !',
    //           type: 'success',
    //         })
    //       })
    //   })
    // },
    // handleDelete() {
    //   if (this.mission.participations_total > 0) {
    //     this.$alert(
    //       'Il est impossible de supprimer une mission déjà assigner à un ou plusieurs bénévoles.',
    //       'Supprimer la mission',
    //       {
    //         confirmButtonText: 'Retour',
    //         type: 'warning',
    //         center: true,
    //       }
    //     )
    //   } else {
    //     this.$confirm(
    //       `La mission ${this.mission.name} sera définitivement supprimée de la plateforme. Voulez-vous continuer ?`,
    //       'Supprimer la mission',
    //       {
    //         confirmButtonText: 'Supprimer',
    //         confirmButtonClass: 'el-button--danger',
    //         cancelButtonText: 'Annuler',
    //         center: true,
    //         type: 'error',
    //       }
    //     ).then(() => {
    //       deleteMission(this.mission.id).then(() => {
    //         this.$message({
    //           type: 'success',
    //           message: `La mission ${this.mission.name} a été supprimée.`,
    //         })
    //         this.$router.push('/dashboard/missions')
    //       })
    //     })
    //   }
    // },
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
