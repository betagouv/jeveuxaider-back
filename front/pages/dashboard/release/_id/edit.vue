<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Release</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl text-gray-800">
        {{ release.name }}
      </div>
    </div>
    <FormRelease :release="release" class="max-w-2xl" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const release = await $api.getRelease(params.id)
    return {
      release,
    }
  },
  methods: {},
}
</script>
