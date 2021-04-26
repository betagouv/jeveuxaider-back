<template>
  <Page :page="page" />
</template>

<script>
export default {
  async asyncData({ $api }) {
    const page = await $api.getPage(39)
    return {
      page,
    }
  },
  head() {
    return {
      title: 'Accessibilité | JeVeuxAider.gouv.fr',
      link: [
        {
          rel: 'canonical',
          href: 'https://www.jeveuxaider.gouv.fr/accessibilite',
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            "La plateforme JeVeuxAider.gouv.fr est un service public numérique destiné à organiser l'engagement civique bénévole en France. Elle permet à toute personne âgée de plus de 16 ans de s’engager dans des missions de bénévolat.",
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.png',
        },
      ],
    }
  },
}
</script>
