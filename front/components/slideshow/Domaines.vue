<template>
  <div>
    <VueSlickCarousel ref="vueSlickCarousel" v-bind="settings">
      <nuxt-link
        v-for="domaine in domaines"
        :key="domaine.id"
        class="card--domaine--wrapper"
        :to="`/domaines-action/${domaine.slug}`"
      >
        <CardDomaine :domaine="domaine" class="!h-full" />
      </nuxt-link>

      <template #prevArrow="arrowOption">
        <transition name="fade">
          <div
            v-show="arrowOption.currentSlide"
            class="rounded-full !bg-white transition transform !-translate-x-12 !-translate-y-1/2 hover:scale-125 will-change-transform flex justify-center items-center !p-6 relative z-10"
          >
            <img
              src="/images/chevron_left.svg"
              alt="Précédent"
              class="absolute inset-0 m-auto"
            />
          </div>
        </transition>
      </template>

      <template #nextArrow="arrowOption">
        <transition name="fade">
          <div
            v-show="arrowOption.currentSlide < domaines.length - 1"
            class="rounded-full !bg-white transition transform !translate-x-12 !-translate-y-1/2 hover:scale-125 will-change-transform flex justify-center items-center !p-6 relative z-10"
          >
            <img
              src="/images/chevron_right.svg"
              alt="Suivant"
              class="absolute inset-0 m-auto"
            />
          </div>
        </transition>
      </template>

      <template #customPaging>
        <div class="text-sm text-[#DEDEDE]">
          <div>●</div>
        </div>
      </template>
    </VueSlickCarousel>
  </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel'
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'

export default {
  components: { VueSlickCarousel },
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
      settings: {
        arrows: true,
        dots: true,
        speed: 500,
        edgeFriction: 0,
        touchThreshold: 100,
        swipeToSlide: true,
        infinite: false,
        variableWidth: true,
      },
    }
  },
  mounted() {
    const dotsWrapper = this.$refs?.vueSlickCarousel?.$el
      ?.getElementsByClassName('slick-dots')
      ?.item(0)

    if (dotsWrapper) {
      const wrapper = document.createElement('div')
      wrapper.className = 'mt-6'
      dotsWrapper.parentNode.insertBefore(wrapper, dotsWrapper)
      wrapper.appendChild(dotsWrapper)
    }
  },
}
</script>

<style lang="postcss" scoped>
.card--domaine--wrapper {
  @apply flex flex-col max-w-[360px];
  width: calc(100vw - 64px) !important;
  @screen sm {
    @apply max-w-[350px] w-full;
  }
}

.slick-slider {
  ::v-deep {
    .slick-slide {
      height: auto !important;
    }

    .slick-list {
      overflow: visible;
    }

    .slick-track {
      @apply space-x-6 flex items-stretch;
      @screen xl {
        @apply space-x-14;
      }
      > div > div {
        height: 100%;
      }
    }

    .slick-arrow {
      box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.15);
      display: none;
      @screen sm {
        display: block;
      }
    }

    .slick-dots {
      position: inherit;
      @apply !space-x-3 text-center sm:text-left bottom-0 w-auto flex-none;
      > li {
        @apply w-auto h-auto m-0;
        &.slick-active > div {
          color: #696974;
        }
      }
    }
  }
}
</style>
