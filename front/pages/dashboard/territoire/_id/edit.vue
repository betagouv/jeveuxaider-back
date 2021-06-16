<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Territoire</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2-5xl text-gray-800">
        {{ territoire.name }}
      </div>
    </div>
    <FormTerritoire :territoire="territoire" class="max-w-2xl" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
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

    const territoire = await $api.getTerritoire(params.id)
    return {
      territoire,
    }
  },
  methods: {},
}
</script>
