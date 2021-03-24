<template>
  <div class="profile-form max-w-2xl pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Collectivité</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl">
        {{ collectivity.name }}
      </div>
    </div>

    <FormCollectivity :collectivity="collectivity" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      !['admin', 'referent', 'referent_regional', 'responsable'].includes(
        store.getters.contextRole
      )
    ) {
      return error({ statusCode: 403 })
    }
    if (store.getters.contextRole == 'responsable') {
      if (store.getters.structure.collectivity.id != params.id) {
        return error({ statusCode: 403 })
      }
    }
    const collectivity = await $api.getCollectivity(params.id)
    return {
      collectivity,
    }
  },
}
</script>
