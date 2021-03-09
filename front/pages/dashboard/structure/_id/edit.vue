<template>
  <div class="structure-form pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Organisation</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl text-gray-800">
        {{ structure.name }}
      </div>
      <TagModelState
        :state="structure.state"
        class="relative ml-3"
        style="top: 1px"
      />
    </div>

    <FormStructure :structure="structure" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const structure = await $api.getStructure(params.id)
    return {
      structure,
    }
  },
  methods: {},
}
</script>
