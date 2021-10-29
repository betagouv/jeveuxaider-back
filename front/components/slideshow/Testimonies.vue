<template>
  <Slideshow :slides-count="testimonies.length" :settings="settings">
    <div v-for="testimony in testimonies" :key="testimony.id">
      <div
        class="testimony--wrapper flex flex-col items-center space-y-6 text-center max-w-[768px] mx-auto"
      >
        <img
          :src="testimony.organization.logo.default"
          :srcset="
            testimony.organization.logo.x2
              ? `${testimony.organization.logo.x2} 2x`
              : false
          "
          :alt="testimony.organization.name"
          class="max-w-[150px] max-h-[60px] object-contain w-full h-full"
          data-not-lazy
        />

        <div class="text-xl lg:text-2xl leading-relaxed">
          “{{ testimony.content | decodeHTMLEntities }}”
        </div>

        <div class="flex items-center space-x-4">
          <img
            :src="testimony.author.image.default"
            :srcset="
              testimony.author.image.x2
                ? `${testimony.author.image.x2} 2x`
                : false
            "
            :alt="testimony.author.name"
            width="40"
            height="40"
            class="flex-none rounded-full overflow-hidden"
          />

          <div class="text-left">
            <span class="font-bold text-[#111827]">
              {{ testimony.author.name }}
            </span>
            <span
              class="text-[#A7A7B0] font-bold mx-2 text-lg hidden sm:inline-block"
              >/</span
            >
            <span class="text-[#A7A7B0] block sm:inline">
              Bénévole chez
              <span class="uppercase font-bold">
                {{ testimony.organization.name }}
              </span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </Slideshow>
</template>

<script>
import Slideshow from '@/components/Slideshow'

export default {
  components: { Slideshow },
  data() {
    return {
      testimonies: [
        {
          id: 1,
          content: `Experience très positive qui m'a permise d'être utile envers les autres, mais aussi de rencontrer des belles personnes.
Je me suis désormais active dans la Banque Alimentaire près de chez moi deux matinées par semaine&nbsp;🙂`,
          author: {
            name: `Anne-Marie`,
            image: {
              default: `/images/temoignages/portraits/annemarie.jpg`,
              x2: `/images/temoignages/portraits/annemarie@2x.jpg`,
            },
          },
          organization: {
            name: 'Banque Alimentaire',
            logo: {
              default: `/images/temoignages/organisations/banques_alimentaires.png`,
              x2: `/images/temoignages/organisations/banques_alimentaires@2x.png`,
            },
          },
        },
        {
          id: 2,
          content: `Une expérience formidable avec un super groupe de vacanciers et d’accompagnateurs. On s'est vite sentis comme en famille. Hâte de partir à nouveau avec l’APF l’année prochaine &nbsp;!`,
          author: {
            name: `Romain`,
            image: {
              default: `/images/temoignages/portraits/romain.jpg`,
              x2: `/images/temoignages/portraits/romain@2x.jpg`,
            },
          },
          organization: {
            name: 'APF Evasion France Handicap',
            logo: {
              default: `/images/temoignages/organisations/APF_Evasion_France_Handicap.svg`,
              x2: null,
            },
          },
        },
      ],
      settings: {
        arrows: true,
        dots: true,
        speed: 500,
        edgeFriction: 0,
        touchThreshold: 100,
        infinite: true,
      },
    }
  },
}
</script>

<style lang="postcss" scoped>
.testimony--wrapper {
  @screen sm {
    width: calc(100% - 150px);
  }
}

::v-deep .slick-slider {
  .wrapper--slick-dots {
    display: block !important;
  }

  .slick-dots {
    @apply !text-center;
  }

  .slick-track {
    @apply space-x-0;
  }

  .slick-list {
    overflow: hidden;
  }
}
</style>
