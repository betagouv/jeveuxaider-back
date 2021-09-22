<template>
  <div class="structure-form pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Réseau</div>
    <div class="mb-8 flex">
      <div class="font-bold text-[1.75rem] text-[#242526]">
        Création d'un nouveau réseau
      </div>
    </div>

    <FormReseau />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  asyncData({ $api, store, error, params }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
}
</script>
