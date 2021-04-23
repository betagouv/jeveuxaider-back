<template>
  <Page :page="page" />
</template>

<script>
export default {
  async asyncData({ $api }) {
    const page = await $api.getPage(4)
    return {
      page,
    }
  },
  head() {
    return {
      title: 'Charte de la Réserve Civique | JeVeuxAider.gouv.fr',
      link: [
        {
          rel: 'canonical',
          href: 'https://www.jeveuxaider.gouv.fr/charte-reserve-civique',
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            'La réserve civique permet à toute personne qui le souhaite de s’engager à servir les valeurs de la République en participant à des missions d’intérêt général, à titre bénévole et occasionnel.',
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/jva-logo.png',
        },
      ],
    }
  },
}
</script>
