<template>
  <TerritoirePage :territoire="territoire" />
</template>

<script>
export default {
  async asyncData({ $api, params, error }) {
    const territoire = await $api.getTerritoire(params.slug)
    if (
      !territoire ||
      !territoire.is_published ||
      territoire.state != 'validated'
    ) {
      return error({ statusCode: 404 })
    }

    return {
      territoire,
    }
  },
  head() {
    return {
      title: `Devenez bénévole ${this.territoire.suffix_title} | Bénévolat ${this.territoire.name} | JeVeuxAider.gouv.fr`,
      link: [
        {
          rel: 'canonical',
          href: `https://www.jeveuxaider.gouv.fr${this.territoire.full_url}`,
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: `Trouvez une mission de bénévolat ${this.territoire.suffix_title} parmi les missions actuellement disponibles et faites vivre l'engagement de chacun pour tous`,
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
      ],
    }
  },
}
</script>
