export default {
  props: {
    mission: {
      type: Object,
      default: null,
    },
  },
  computed: {
    thumbnail() {
      const thumbnail = this.mission.template_id
        ? `templates/${this.mission.template_id}`
        : this.mission.thumbnail
        ? `domaines/${this.mission.thumbnail}`
        : `domaines/${this.mission.domaine_id}_1`

      return {
        default: `/images/${thumbnail}.jpg`,
        x2: `/images/${thumbnail}@2x.jpg`,
      }
    },
  },
}
