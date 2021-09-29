<template>
  <div class="reseau-view">
    <HeaderReseau :reseau="reseau">
      <template #action>
        <DropdownReseauButton :reseau="reseau" />
      </template>
    </HeaderReseau>
    <NavTabReseau :reseau="reseau" />
    <div class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl font-semibold">Informations</div>
          </div>
          <ModelReseauInfos :reseau="reseau" />
        </el-card>
        <el-card v-if="responsables" shadow="never" class="p-4">
          <div class="flex justify-between items-start">
            <div class="mb-6 text-xl font-semibold">Responsables du réseau</div>
            <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/responsables`">
              <el-button size="small" type="secondary">
                Gérer les responsables
              </el-button>
            </nuxt-link>
          </div>
          <div v-if="responsables.length > 0" class="grid grid-cols-2 gap-3">
            <ModelMemberTeaser
              v-for="member in responsables"
              :key="member.id"
              class="member py-2"
              :member="member"
            />
          </div>
          <div v-else>
            <EmptyState
              title="Aucun responsable"
              subtitle="Il n'y a aucun responsable qui gère ce réseau"
              button-title="Ajouter un responsable"
              :button-link="`/dashboard/reseaux/${reseau.id}/responsables/add`"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="mx-auto h-12 w-12 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                />
              </svg>
            </EmptyState>
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
    const reseau = await $api.getReseau(params.id)
    const responsables = await $api.getReseauResponsables(params.id)
    return {
      reseau,
      responsables: responsables.data,
    }
  },
}
</script>

<style scoped lang="postcss">
.el-menu--horizontal {
  @apply px-12;
  > .el-menu-item {
    @apply mr-8 p-0 font-medium;
    border-bottom: solid 3px #070191;
  }
}
</style>
