<script>
export default {
  middleware({ $api, route, redirect }) {
    redirect(301, `/departements/${route.params.slug}`)
  },
}
</script>
