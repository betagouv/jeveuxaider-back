<script>
export default {
  async middleware({ $api, route, redirect }) {
    const territoire = await $api.getTerritoire(route.params.slug)
    if (territoire.type == 'department') {
      redirect(301, `/territoires/departements/${route.params.slug}`)
    } else {
      redirect(301, `/territoires/collectivites/${route.params.slug}`)
    }
  },
}
</script>
