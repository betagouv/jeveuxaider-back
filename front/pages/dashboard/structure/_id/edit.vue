<template>
  <div class="structure-form pl-12 pb-12 max-w-3xl">
    <div class="text-m text-gray-600 uppercase">Organisation</div>
    <div class="mb-8 pb-4 flex items-center border-b border-gray-200">
      <div class="font-bold text-2-5xl text-gray-800">
        {{ structure.name }}
      </div>
      <TagModelState
        :state="structure.state"
        class="relative ml-3"
        style="top: 1px"
      />
    </div>

    <FormStructure :structure="structure" :domaines="domaines" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const structure = await $api.getStructure(params.id)
    const tags = await $api.fetchTags({ 'filter[type]': 'domaine' })
    return {
      structure,
      domaines: tags.data.data,
    }
  },
  methods: {},
}
</script>
