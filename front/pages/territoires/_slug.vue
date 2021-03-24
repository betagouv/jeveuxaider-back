<script>
export default {
  async middleware({ $api, route, redirect }) {
    const collectivity = await $api.getCollectivity(route.params.slug)
    if (collectivity.type == 'department') {
      redirect(`/territoires/departements/${route.params.slug}`)
    } else {
      redirect(`/territoires/collectivites/${route.params.slug}`)
    }
  },
}
</script>
