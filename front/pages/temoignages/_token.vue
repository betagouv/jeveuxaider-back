<template>
  <div>
    <TemoignageAlreadyExist v-if="temoignage" :temoignage="temoignage" />

    <template v-else>
      <transition name="fade" mode="out-in">
        <TemoignageGrade
          v-if="currentStep.slug == 'grade'"
          key="TemoignageGrade"
          :mission="mission"
          :benevole="benevole"
          :initial-form="form"
          @rating-selected="onRatingSelected"
        />

        <TemoignageTestimony
          v-else-if="currentStep.slug == 'testimony'"
          key="TemoignageTestimony"
          :mission="mission"
          :benevole="benevole"
          :initial-form="form"
          @submit="onTestimonySubmit"
          @destroy="onDestroy"
        />

        <TemoignageThanks
          v-else-if="currentStep.slug == 'thanks'"
          key="TemoignageThanks"
          :mission="mission"
          :benevole="benevole"
          :initial-form="form"
        />
      </transition>
    </template>
  </div>
</template>

<script>
export default {
  layout: 'temoignage',
  async asyncData({ $api, params, error, store, $axios }) {
    const { data: notificationTemoignage } = await $axios.get(
      `/notification-temoignage/${params.token}`
    )
    if (!notificationTemoignage) {
      return error({ statusCode: 404 })
    }

    const { data: mission } = await $axios.get(
      `/participation/${notificationTemoignage.participation_id}/mission`
    )

    if (!mission || !mission.structure || mission.state != 'Terminée') {
      return error({ statusCode: 404 })
    }

    const { data: benevole } = await $axios.get(
      `/participation/${notificationTemoignage.participation_id}/benevole-name`
    )

    const { data: temoignage } = await $axios.get(
      `/participation/${notificationTemoignage.participation_id}/temoignage`
    )

    return {
      notificationTemoignage,
      benevole,
      temoignage,
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
      return this.$store.getters['temoignage/step']
    },
  },
  methods: {
    onRatingSelected(payload) {
      this.form = payload
      this.$store.dispatch('temoignage/nextStep')
    },
    async onTestimonySubmit(payload) {
      this.form = payload
      this.$store.dispatch('temoignage/nextStep')

      await this.$axios.post(`/temoignage`, {
        ...this.form,
        participation_id: this.notificationTemoignage.participation_id,
      })
    },
    onDestroy(payload) {
      this.form = payload
    },
  },
}
</script>
