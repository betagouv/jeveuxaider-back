<template>
  <Page :page="page" />
</template>

<script>
export default {
  async asyncData({ $api }) {
    const page = await $api.getPage(1)
    return {
      page,
    }
  },
  head() {
    return {
      title: 'Mentions légales | JeVeuxAider.gouv.fr',
      link: [
        {
          rel: 'canonical',
          href: 'https://www.jeveuxaider.gouv.fr/mentions-legales',
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            "La plateforme JeVeuxAider.gouv.fr est un service public numérique destiné à organiser l'engagement civique bénévole en France. Elle permet à toute personne âgée de plus de 16 ans de s’engager dans des missions de bénévolat.",
        },
      ],
    }
  },
}
</script>
