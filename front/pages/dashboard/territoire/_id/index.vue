<template>
  <div class="territoire-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1 mb-8">
        <div class="text-m text-gray-600 uppercase">Territoire</div>
        <div class="flex items-center flex-wrap">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
            {{ territoire.name }}
          </div>
          <TagModelState v-if="territoire.state" :state="territoire.state" />
          <el-tag v-if="territoire.is_published" size="medium" class="m-1 ml-0">
            En ligne
          </el-tag>
          <el-tag
            v-if="!territoire.is_published"
            size="medium"
            class="m-1 ml-0"
          >
            Hors ligne
          </el-tag>
        </div>
        <div
          v-if="territoire.state == 'validated' && territoire.is_published"
          class="mt-2 flex items-center"
        >
          <div class="mr-2 text-gray-450">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
          <nuxt-link target="_blank" :to="`${territoire.full_url}`">
            <span class="text-sm underline hover:no-underline">
              {{ $config.appUrl }}{{ territoire.full_url }}
            </span>
          </nuxt-link>
        </div>
      </div>
      <div>
        <DropdownTerritoireButton :territoire="territoire" />
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/territoire/${territoire.id}`">
        Informations
      </el-menu-item>
      <el-menu-item
        :index="`/dashboard/territoire/${territoire.id}/statistics`"
      >
        Statistiques
      </el-menu-item>
      <el-menu-item
        v-if="$store.getters.contextRole == 'admin'"
        :index="`/dashboard/territoire/${territoire.id}/history`"
      >
        Historique
      </el-menu-item>
    </el-menu>

    <div class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl font-semibold">Territoire</div>
          </div>
          <ModelTerritoireInfos :territoire="territoire" />
        </el-card>
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div
              v-if="territoire.responsables"
              class="mb-6 text-xl font-semibold"
            >
              Responsables
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <ModelMemberTeaser
              v-for="responsable in territoire.responsables"
              :key="responsable.id"
              class="member py-2"
              :member="responsable"
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
  async asyncData({ $api, params, store, error }) {
    const territoire = await $api.getTerritoire(params.id)

    if (
      !['admin', 'responsable_territoire'].includes(store.getters.contextRole)
    ) {
      return error({ statusCode: 403 })
    }

    if (store.getters.contextRole == 'responsable_territoire') {
      if (
        !store.getters.user.profile.territoires.filter(
          (item) => item.id == params.id
        ).length
      ) {
        return error({ statusCode: 403 })
      }
    }

    return {
      territoire,
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
