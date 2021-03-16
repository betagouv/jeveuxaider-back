<template>
  <Page :page="page" />
</template>

<script>
export default {
  async asyncData({ $api }) {
    const page = await $api.getPage(1)
    return {
      page,
    }
  },
}
</script>
