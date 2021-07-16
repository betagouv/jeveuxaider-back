<script>
export default {
  name: 'MissionMixin',
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
  methods: {
    defaultThumbnail(e) {
      let file = 'card-thumbnail-default'

      if (
        (this.domaine_id && this.domaine_id == 3) ||
        (this.mission && this.mission.domaine_name == 'Santé pour tous')
      ) {
        file = 'templates/sante_pour_tous'
      }

      e.target.src = `/images/${file}.jpg`
      e.target.srcset = `/images/${file}@2x.jpg 2x`
    },
  },
}
</script>
