<template>
  <div>Redirection en cours</div>
</template>

<script>
export default {
  async asyncData({ $api, params, redirect, route }) {
    const mission = await $api.getMission(params.id)
    const query = new URLSearchParams(route.query)
    let urlWithSlug = `/missions-benevolat/${mission.id}/${mission.slug}`
    if (route.query) {
      urlWithSlug = `${urlWithSlug}?${query.toString()}`
    }
    redirect(301, urlWithSlug)

    return {
      mission,
    }
  },
}
</script>
