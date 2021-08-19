<template>
  <div class="structure-view">
    <HeaderOrganisation :structure="structure">
      <template #action>
        <DropdownStructureButton :structure="structure" />
      </template>
    </HeaderOrganisation>
    <NavTabStructure
      v-if="$store.getters.contextRole != 'responsable'"
      :structure="structure"
    />
    <div class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl font-semibold">Organisation</div>
          </div>
          <ModelStructureInfos :structure="structure" />
        </el-card>
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between items-start">
            <div v-if="structure.members" class="mb-6 text-xl font-semibold">
              Membres
            </div>
            <nuxt-link :to="`/dashboard/structure/${structure.id}/members`">
              <el-button size="small" type="secondary">
                Gérer les membres
              </el-button>
            </nuxt-link>
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

<style scoped lang="postcss">
.el-menu--horizontal {
  @apply px-12;
  > .el-menu-item {
    @apply mr-8 p-0 font-medium;
    border-bottom: solid 3px #070191;
  }
}
</style>
