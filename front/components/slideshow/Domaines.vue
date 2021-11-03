<template>
  <Slideshow :slides-are-links="true" :slides-count="domaines.length">
    <nuxt-link
      v-for="domaine in domaines"
      :key="domaine.id"
      class="card--domaine--wrapper"
      :to="`/domaines-action/${domaine.slug}`"
    >
      <CardDomaine :domaine="domaine" class="!h-full" />
    </nuxt-link>
  </Slideshow>
</template>

<script>
import Slideshow from '@/components/Slideshow'

export default {
  components: { Slideshow },
  data() {
    return {
      domaines: [
        {
          id: 1,
          name: 'Mobilisation Covid-19',
          slug: 'mobilisation-covid-19',
          description: 'Mobilisez-vous pour la lutte contre la Covid-19',
          image:
            '/images/card_domaine_covid19.webp, /images/card_domaine_covid19@2x.webp 2x, /images/card_domaine_covid19.jpg, /images/card_domaine_covid19@2x.jpg 2x',
          bottom: true,
        },
        {
          id: 2,
          name: 'Éducation pour tous',
          slug: 'education-pour-tous',
          description: `Mobilisez-vous pour l'éducation`,
          image:
            '/images/card_domaine_education.webp, /images/card_domaine_education@2x.webp 2x, /images/card_domaine_education.jpg, /images/card_domaine_education@2x.jpg 2x',
          bottom: true,
        },
        {
          id: 3,
          name: 'Santé pour tous',
          slug: 'sante-pour-tous',
          description:
            'Venez en aide aux malades, à leurs proches ou aux soignants',
          image:
            '/images/card_domaine_sante.webp, /images/card_domaine_sante@2x.webp 2x, /images/card_domaine_sante.jpg, /images/card_domaine_sante@2x.jpg 2x',
          bottom: true,
        },
        {
          id: 4,
          name: 'Protection de la nature',
          slug: 'protection-de-la-nature',
          description:
            'Aidez les associations locales à protéger l’environnement',
          image:
            '/images/card_domaine_nature.webp, /images/card_domaine_nature@2x.webp 2x, /images/card_domaine_nature.jpg, /images/card_domaine_nature@2x.jpg 2x',
          bottom: true,
        },
        {
          id: 6,
          name: 'Solidarité et insertion',
          slug: 'solidarite-et-insertion',
          description:
            'Venez en aide aux plus démunis et aux personnes isolées',
          image:
            '/images/card_domaine_solidarite.webp, /images/card_domaine_solidarite@2x.webp 2x, /images/card_domaine_solidarite.jpg, /images/card_domaine_solidarite@2x.jpg 2x',
          bottom: true,
        },
        {
          id: 7,
          name: 'Sport pour tous',
          slug: 'sport-pour-tous',
          description: `Mobilisez-vous pour le sport`,
          image:
            '/images/card_domaine_sport.webp, /images/card_domaine_sport@2x.webp 2x, /images/card_domaine_sport.jpg, /images/card_domaine_sport@2x.jpg 2x',
          bottom: true,
        },
        {
          id: 8,
          name: 'Prévention et protection',
          slug: 'prevention-et-protection',
          description: `Mobilisez-vous pour la prévention et la protection civile`,
          image:
            '/images/card_domaine_prevention.webp, /images/card_domaine_prevention@2x.webp 2x, /images/card_domaine_prevention.jpg, /images/card_domaine_prevention@2x.jpg 2x',
          bottom: true,
        },
        {
          id: 9,
          name: 'Mémoire et citoyenneté',
          slug: 'memoire-et-citoyennete',
          description: `Mobilisez-vous pour la mémoire et la citoyenneté`,
          image:
            '/images/card_domaine_memoire.webp, /images/card_domaine_memoire@2x.webp 2x, /images/card_domaine_memoire.jpg, /images/card_domaine_memoire@2x.jpg 2x',
          bottom: true,
        },
        {
          id: 10,
          name: 'Coopération internationale',
          slug: 'cooperation-internationale',
          description: `Mobilisez-vous pour la coopération internationale`,
          image:
            '/images/card_domaine_cooperation.webp, /images/card_domaine_cooperation@2x.webp 2x, /images/card_domaine_cooperation.jpg, /images/card_domaine_cooperation@2x.jpg 2x',
          bottom: true,
        },
        {
          id: 11,
          name: 'Art & Culture pour tous',
          slug: 'art-and-culture-pour-tous',
          description: `Mobilisez-vous pour l'art et la culture`,
          image:
            '/images/card_domaine_art.webp, /images/card_domaine_art@2x.webp 2x, /images/card_domaine_art.jpg, /images/card_domaine_art@2x.jpg 2x',
          bottom: true,
        },
      ],
    }
  },
}
</script>

<style lang="postcss" scoped>
.card--domaine--wrapper {
  @apply !flex flex-col max-w-[360px] transition rounded-[10px] h-full;
  width: calc(100vw - 64px) !important;
  @screen sm {
    @apply max-w-[350px] w-full;
  }
}

::v-deep .slick-slider {
  .slick-arrow {
    &.slick-prev {
      @apply translate-x-[-104px];
    }
    &.slick-next {
      @apply translate-x-[104px];
    }
  }

  .slick-track {
    @apply space-x-4;
    @screen sm {
      @apply space-x-6;
    }
    @screen xl {
      @apply space-x-14;
    }
  }
}
</style>
