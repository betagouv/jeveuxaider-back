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
        </div>
        <div class="font-light text-gray-600 flex items-center">
          <div
            :class="territoire.is_published ? 'bg-green-500' : 'bg-red-500'"
            class="rounded-full h-2 w-2 mr-2"
          ></div>
          <nuxt-link
            v-if="territoire.is_published"
            :to="territoire.full_url"
            target="_blank"
            class="underline hover:no-underline"
          >
            {{ territoire.full_url }}
          </nuxt-link>
          <span v-else class="cursor-default">
            {{ territoire.full_url }}
          </span>
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

    if (!['admin', 'responsable'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }

    if (store.getters.contextRole == 'responsable') {
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
