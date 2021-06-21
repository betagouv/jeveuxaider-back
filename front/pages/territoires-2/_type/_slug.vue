<template>
  <TerritoirePage :territoire="territoire" />
</template>

<script>
export default {
  async asyncData({ $api, params, error }) {
    const territoire = await $api.getTerritoire(params.slug)
    if (
      !territoire ||
      !territoire.is_published ||
      territoire.state != 'validated'
    ) {
      return error({ statusCode: 404 })
    }

    return {
      territoire,
    }
  },
}
</script>
