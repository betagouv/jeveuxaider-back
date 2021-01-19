<script>
export default {
  name: 'MissionMixin',
  props: {
    mission: {
      type: Object,
      default: null,
    },
  },
  computed: {
    thumbnail() {
      let thumbnail = this.mission.thumbnail
        ? this.mission.thumbnail
        : `${this.mission.domaine_id}_1`
      return {
        default: `/images/domaines/${thumbnail}.jpg`,
        x2: `/images/domaines/${thumbnail}@2x.jpg`,
      }
    },
  },
}
</script>
