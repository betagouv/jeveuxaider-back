<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Document</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2-5xl text-[#242526]">
        Création d'un nouveau document
      </div>
    </div>
    <FormDocument
      :document="{ roles: ['referent', 'responsable'] }"
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
