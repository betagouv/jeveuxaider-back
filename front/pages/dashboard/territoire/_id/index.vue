<template>
  <div class="territoire-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1 mb-8">
        <div class="text-m text-gray-600 uppercase">
          {{ territoire.type | labelFromValue('territoires_types') }}
        </div>
        <div class="flex items-center flex-wrap">
          <div class="font-bold text-[1.75rem] text-[#242526] mr-2">
            {{ territoire.name }}
          </div>
          <TagModelState v-if="territoire.state" :state="territoire.state" />
        </div>
        <div class="font-light text-gray-600 flex items-center">
          <div
            :class="territoire.is_published ? 'bg-[#0e9f6e]' : 'bg-[#f56565]'"
            class="rounded-full h-2 w-2 mr-2 flex-none"
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

    <NavTabTerritoire
      v-if="$store.getters.contextRole != 'responsable'"
      :territoire="territoire"
    />

    <div class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl font-semibold">Territoire</div>
          </div>
          <ModelTerritoireInfos :territoire="territoire" />
        </el-card>
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between items-start">
            <div
              v-if="territoire.responsables"
              class="mb-6 text-xl font-semibold"
            >
              Responsables
            </div>
            <nuxt-link
              :to="`/dashboard/territoire/${territoire.id}/responsables`"
            >
              <el-button size="small" type="secondary">
                Gérer les responsables
              </el-button>
            </nuxt-link>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <template
              v-if="territoire.responsables && !territoire.responsables.length"
            >
              Aucun responsable
            </template>

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

<style scoped lang="postcss">
.el-menu--horizontal {
  @apply px-12;
  > .el-menu-item {
    @apply mr-8 p-0 font-medium;
    border-bottom: solid 3px #070191;
  }
}
</style>
