<template>
  <div>Redirection en cours</div>
</template>

<script>
export default {
  async asyncData({ $api, params, redirect }) {
    const mission = await $api.getMission(params.id)

    redirect(301, `/missions-benevolat/${mission.id}/${mission.slug}`)

    return {
      mission,
    }
  },
}
</script>
