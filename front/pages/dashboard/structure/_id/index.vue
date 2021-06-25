<template>
  <div class="structure-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Organisation</div>
        <div class="flex items-center flex-wrap">
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
        <div
          v-if="structure.statut_juridique == 'Association'"
          class="font-light text-gray-600 flex items-center"
        >
          <div
            :class="
              structure.state == 'Validée' ? 'bg-green-500' : 'bg-red-500'
            "
            class="rounded-full h-2 w-2 mr-2"
          ></div>
          <nuxt-link
            v-if="structure.state == 'Validée'"
            :to="structure.full_url"
            target="_blank"
            class="underline hover:no-underline"
          >
            {{ structure.full_url }}
          </nuxt-link>
          <span v-else class="cursor-default">
            {{ structure.full_url }}
          </span>
        </div>
      </div>
      <div>
        <DropdownStructureButton
          v-if="['responsable', 'admin'].includes($store.getters.contextRole)"
          :structure="structure"
        />
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="my-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/structure/${structure.id}`">
        Informations
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${structure.id}/statistics`">
        Statistiques
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

    <div class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl font-semibold">Organisation</div>
          </div>
          <ModelStructureInfos :structure="structure" />
        </el-card>
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div v-if="structure.members" class="mb-6 text-xl font-semibold">
              Membres
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <ModelMemberTeaser
              v-for="member in structure.members"
              :key="member.id"
              class="member py-2"
              :member="member"
            />
          </div>
        </el-card>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const structure = await $api.getStructure(params.id)
    return {
      structure,
    }
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
