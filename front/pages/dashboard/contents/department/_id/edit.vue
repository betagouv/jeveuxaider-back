<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Département</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl text-gray-800">
        {{ collectivity.name }}
      </div>
    </div>
    <FormCollectivity :collectivity="collectivity" class="max-w-2xl" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const collectivity = await $api.getCollectivity(params.id)
    return {
      collectivity,
    }
  },
  methods: {},
}
</script>
