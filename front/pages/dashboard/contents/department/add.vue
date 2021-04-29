<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Département</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2-5xl text-gray-800">
        Création d'un nouveau département
      </div>
    </div>
    <FormCollectivity
      :collectivity="{
        type: 'departement',
      }"
      class="max-w-2xl"
    />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  asyncData({ $api, params, store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
}
</script>
