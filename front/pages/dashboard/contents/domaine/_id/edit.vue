<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Domaine</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl text-gray-800">
        {{ domaine.name }}
      </div>
    </div>
    <FormDomaine :domaine="domaine" :tags="tags" class="max-w-2xl" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const domaine = await $api.getThematique(params.id)
    const tags = await $api.fetchTags({ 'filter[type]': 'domaine' })
    return {
      tags: tags.data.data,
      domaine,
    }
  },
  methods: {},
}
</script>
