<template>
  <div>
    <AvisAlreadyExist v-if="avis" :avis="avis" />

    <template v-else>
      <AvisGrade
        v-if="currentStep.slug == 'grade'"
        :mission="mission"
        :benevole="benevole"
        :initial-form="form"
        @rating-selected="onRatingSelected"
      />

      <AvisTestimony
        v-else-if="currentStep.slug == 'testimony'"
        :mission="mission"
        :benevole="benevole"
        :initial-form="form"
        @submit="onTestimonySubmit"
        @destroy="onDestroy"
      />

      <AvisThanks
        v-else-if="currentStep.slug == 'thanks'"
        :mission="mission"
        :benevole="benevole"
        :initial-form="form"
      />
    </template>
  </div>
</template>

<script>
export default {
  layout: 'avis',
  async asyncData({ $api, params, error, store, $axios }) {
    const { data: notificationAvis } = await $axios.get(
      `/notification-avis/${params.token}`
    )
    if (!notificationAvis) {
      return error({ statusCode: 404 })
    }

    const { data: mission } = await $axios.get(
      `/participation/${notificationAvis.participation_id}/mission`
    )

    if (!mission || !mission.structure || mission.state != 'Terminée') {
      return error({ statusCode: 404 })
    }

    const { data: benevole } = await $axios.get(
      `/participation/${notificationAvis.participation_id}/benevole-name`
    )

    const { data: avis } = await $axios.get(
      `/participation/${notificationAvis.participation_id}/avis`
    )

    return {
      notificationAvis,
      benevole,
      avis,
      mission,
    }
  },
  data() {
    return {
      form: {},
    }
  },
  head() {
    return {
      title: `Votre note est importante et permet de contribuer à l'amélioration des missions de bénévolat.`,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: `Votre note est réservée à l'administration pour améliorer la qualité des missions de bénévolat proposées.`,
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
      ],
    }
  },
  computed: {
    currentStep() {
      return this.$store.getters['avis/step']
    },
  },
  methods: {
    onRatingSelected(payload) {
      this.form = payload
      this.$store.dispatch('avis/nextStep')
    },
    async onTestimonySubmit(payload) {
      this.form = payload
      this.$store.dispatch('avis/nextStep')

      await this.$axios.post(`/avis`, {
        ...this.form,
        participation_id: this.notificationAvis.participation_id,
      })
    },
    onDestroy(payload) {
      this.form = payload
    },
  },
}
</script>
