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
    picture() {
      let picture
      switch (this.mission.domaine_name) {
        case 'Mobilisation covid-19':
          picture = 'covid'
          break

        case 'Solidarité et insertion':
          picture = 'solidarite'
          break

        case 'Éducation pour tous':
          picture = 'education'
          break

        case 'Santé pour tous':
          picture = 'sante'
          break

        case 'Prévention et protection':
          picture = 'prevention'
          break

        case 'Protection de la nature':
          picture = 'nature'
          break

        case 'Art et culture pour tous':
          picture = 'culture'
          break

        case 'Mémoire et citoyenneté':
          picture = 'memoire_citoyennete'
          break

        case 'Coopération internationale':
          picture = 'cooperation_internationale'
          break

        // case 'Sport pour tous':
        //   picture = 'sport'
        //   break

        default:
          picture = null
          break
      }

      return {
        default: picture ? `/images/domaines/${picture}.jpg` : null,
        x2: picture ? `/images/domaines/${picture}@2x.jpg` : null,
      }
    },
  },
}
</script>
