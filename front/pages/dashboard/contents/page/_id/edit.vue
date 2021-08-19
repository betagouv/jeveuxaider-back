<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Page</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2-5xl text-[#242526]">
        {{ page.title }}
      </div>
    </div>
    <FormPage :page="page" class="max-w-2xl" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const page = await $api.getPage(params.id)
    return {
      page,
    }
  },
  methods: {},
}
</script>
