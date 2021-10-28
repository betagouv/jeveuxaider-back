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
          image: {
            default: '/images/card_domaine_covid19.jpg',
            x2: '/images/card_domaine_covid19@2x.jpg',
          },
          bottom: true,
        },
        {
          id: 2,
          name: 'Éducation pour tous',
          slug: 'education-pour-tous',
          description: `Mobilisez-vous pour l'éducation`,
          image: {
            default: '/images/card_domaine_education.jpg',
            x2: '/images/card_domaine_education@2x.jpg',
          },
          bottom: true,
        },
        {
          id: 3,
          name: 'Santé pour tous',
          slug: 'sante-pour-tous',
          description:
            'Venez en aide aux malades, à leurs proches ou aux soignants',
          image: {
            default: '/images/card_domaine_sante.jpg',
            x2: '/images/card_domaine_sante@2x.jpg',
          },
          bottom: true,
        },
        {
          id: 4,
          name: 'Protection de la nature',
          slug: 'protection-de-la-nature',
          description:
            'Aidez les associations locales à protéger l’environnement',
          image: {
            default: '/images/card_domaine_nature.jpg',
            x2: '/images/card_domaine_nature@2x.jpg',
          },
          bottom: true,
        },
        {
          id: 6,
          name: 'Solidarité et insertion',
          slug: 'solidarite-et-insertion',
          description:
            'Venez en aide aux plus démunis et aux personnes isolées',
          image: {
            default: '/images/card_domaine_solidarite.jpg',
            x2: '/images/card_domaine_solidarite@2x.jpg',
          },
          bottom: true,
        },
        {
          id: 7,
          name: 'Sport pour tous',
          slug: 'sport-pour-tous',
          description: `Mobilisez-vous pour le sport`,
          image: {
            default: '/images/card_domaine_sport.jpg',
            x2: '/images/card_domaine_sport@2x.jpg',
          },
          bottom: true,
        },
        {
          id: 8,
          name: 'Prévention et protection',
          slug: 'prevention-et-protection',
          description: `Mobilisez-vous pour la prévention et la protection civile`,
          image: {
            default: '/images/card_domaine_prevention.jpg',
            x2: '/images/card_domaine_prevention@2x.jpg',
          },
          bottom: true,
        },
        {
          id: 9,
          name: 'Mémoire et citoyenneté',
          slug: 'memoire-et-citoyennete',
          description: `Mobilisez-vous pour la mémoire et la citoyenneté`,
          image: {
            default: '/images/card_domaine_memoire.jpg',
            x2: '/images/card_domaine_memoire@2x.jpg',
          },
          bottom: true,
        },
        {
          id: 10,
          name: 'Coopération internationale',
          slug: 'cooperation-internationale',
          description: `Mobilisez-vous pour la coopération internationale`,
          image: {
            default: '/images/card_domaine_cooperation.jpg',
            x2: '/images/card_domaine_cooperation@2x.jpg',
          },
          bottom: true,
        },
        {
          id: 11,
          name: 'Art & Culture pour tous',
          slug: 'art-and-culture-pour-tous',
          description: `Mobilisez-vous pour l'art et la culture`,
          image: {
            default: '/images/card_domaine_art.jpg',
            x2: '/images/card_domaine_art@2x.jpg',
          },
          bottom: true,
        },
      ],
    }
  },
}
</script>

<style lang="postcss" scoped>
.card--domaine--wrapper {
  @apply !flex flex-col max-w-[360px] transition rounded-[10px];
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
