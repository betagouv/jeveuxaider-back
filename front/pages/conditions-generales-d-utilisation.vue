<template>
  <Page :page="page" />
</template>

<script>
export default {
  async asyncData({ $api }) {
    const page = await $api.getPage(2)
    return {
      page,
    }
  },
}
</script>
