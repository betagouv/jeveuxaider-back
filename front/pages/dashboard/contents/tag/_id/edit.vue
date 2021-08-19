<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Tag</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2-5xl text-[#242526]">
        {{ tag.name.fr }}
      </div>
    </div>
    <FormTag :tag="tag" class="max-w-2xl" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const tag = await $api.getTag(params.id)
    return {
      tag,
    }
  },
  methods: {},
}
</script>
