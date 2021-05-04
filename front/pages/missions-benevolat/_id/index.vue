<template>
  <div>
    <MissionApiPage v-if="mission.isFromApi" :mission="mission" />
    <div v-else>Redirection en cours</div>
  </div>
</template>

<script>
export default {
  async asyncData({ $api, params, redirect, route }) {
    const mission = await $api.getMission(params.id)

    if (!mission.isFromApi) {
      const query = new URLSearchParams(route.query)
      let urlWithSlug = `/missions-benevolat/${mission.id}/${mission.slug}`
      if (route.query) {
        urlWithSlug = `${urlWithSlug}?${query.toString()}`
      }
      redirect(301, urlWithSlug)
    }

    return {
      mission,
    }
  },
  head() {
    return {
      title: this.mission.name.substring(0, 80),
      link: [
        {
          rel: 'canonical',
          href: `https://www.jeveuxaider.gouv.fr/missions-benevolat/${this.mission.id}`,
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.mission.description
            .replace(/<\/?[^>]+>/gi, ' ')
            .substring(0, 300),
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
        { hid: 'robots', name: 'robots', content: 'noindex' },
      ],
    }
  },
}
</script>
