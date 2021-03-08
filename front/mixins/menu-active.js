export default {
  name: 'MenuWithActive',
  methods: {
    isActive(item) {
      if (item == 'dashboard') {
        return this.$route.path == '/dashboard'
      } else if (item == 'content') {
        return !!(
          [
            'faq',
            'release',
            'page',
            'thematique',
            'tag',
            'mission-template',
            'document',
          ].find((type) => this.$route.path.includes(type)) ||
          this.$route.path.includes(item)
        )
      } else if (this.$route.path.includes(item)) {
        return true
      }
    },
  },
}
