<template>
  <div class="structure-form px-12 pb-12">
    <div class="flex justify-between">
      <div>
        <div class="text-m text-gray-600 uppercase">Réseau</div>
        <div class="mb-8 flex">
          <div class="font-bold text-[1.75rem] text-[#242526]">
            {{ reseau.name }}
          </div>
        </div>
      </div>
      <div>
        <nuxt-link :to="`/dashboard/reseaux/${$route.params.id}`">
          <el-button>Retour</el-button>
        </nuxt-link>
      </div>
    </div>
    <FormReseau class="max-w-2xl" :reseau="reseau" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, store, error, params }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const reseau = await $api.getReseau(params.id)
    return {
      reseau,
    }
  },
}
</script>
