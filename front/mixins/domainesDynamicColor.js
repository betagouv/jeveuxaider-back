export default {
  // @todo: Supprimer le champ color et utiliser seulement les ids
  computed: {
    bgClass() {
      const color = this.thematique?.color ?? this.color
      let output

      switch (color) {
        case 'sante':
          output = '!bg-sante'
          break
        case 'solidarite':
          output = '!bg-solidarite'
          break
        case 'nature':
          output = '!bg-nature'
          break
        case 'education':
          output = '!bg-education'
          break
        default:
          output = '!bg-primary'
          break
      }

      return output
    },
    colorClass() {
      const color = this.thematique?.color ?? this.color
      let output

      switch (color) {
        case 'sante':
          output = '!text-sante'
          break
        case 'solidarite':
          output = '!text-solidarite'
          break
        case 'nature':
          output = '!text-nature'
          break
        case 'education':
          output = '!text-education'
          break
        default:
          output = '!text-primary'
          break
      }

      return output
    },
  },
}
