<script>
export default {
  middleware({ $api, route, redirect }) {
    redirect(301, `/villes/${route.params.slug}`)
  },
}
</script>
