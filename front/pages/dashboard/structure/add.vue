<template>
  <div class="structure-form pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Organisation</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2-5xl text-gray-800">
        Création d'une nouvelle organisation
      </div>
    </div>

    <FormStructure :domaines="domaines" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, store, error, params }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const tags = await $api.fetchTags({ 'filter[type]': 'domaine' })
    return {
      domaines: tags.data.data,
    }
  },
}
</script>
