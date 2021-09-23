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
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between items-start">
            <div class="mb-6 text-xl font-semibold">Responsables du réseau</div>
            <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/members`">
              <el-button size="small" type="secondary">
                Gérer les membres
              </el-button>
            </nuxt-link>
          </div>
          <div v-if="reseau.profiles.length > 0" class="grid grid-cols-2 gap-3">
            <ModelMemberTeaser
              v-for="member in reseau.profiles"
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
              :button-link="`/dashboard/reseaux/${reseau.id}/members/add`"
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
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between items-start">
            <div class="mb-6 text-xl font-semibold">
              Organisations du réseau
            </div>
            <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/structures/add`">
              <el-button size="small" type="secondary">
                Ajouter une organisation
              </el-button>
            </nuxt-link>
          </div>
          <div
            v-if="reseau.structures"
            class="text-sm divide-y divide divide-gray-100"
          >
            <div
              v-for="structure in reseau.structures"
              :key="structure.id"
              class="py-2"
            >
              {{ structure.name }}
            </div>
          </div>
          <div v-else>
            <EmptyState
              title="Aucune organisation"
              subtitle="Il n'y a aucune organisation liée à ce réseau"
              button-title="Ajouter une organisation"
              :button-link="`/dashboard/reseaux/${reseau.id}/structures/add`"
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
                  d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"
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
    return {
      reseau,
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
