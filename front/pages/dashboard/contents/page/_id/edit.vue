<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Page</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl text-gray-800">
        {{ page.title }}
      </div>
    </div>
    <FormPage :page="page" class="max-w-2xl" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const page = await $api.getPage(params.id)
    return {
      page,
    }
  },
  methods: {},
}
</script>
