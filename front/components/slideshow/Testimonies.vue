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
        />

        <div class="text-xl lg:text-2xl leading-relaxed">
          “{{ testimony.content }}”
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
            class="flex-none"
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
          content: `Une expérience formidable avec un super groupe de vacanciers et d’accompagnateurs. On s'est vite sentis comme en famille. Hâte de m’engager à nouveau avec SINGA !`,
          author: {
            name: `Judith`,
            image: {
              default: `/images/temoignages/portraits/judith.png`,
              x2: `/images/temoignages/portraits/judith@2x.png`,
            },
          },
          organization: {
            name: 'Singa',
            logo: {
              default: `/images/temoignages/organisations/singa.png`,
              x2: `/images/temoignages/organisations/singa@2x.png`,
            },
          },
        },
        {
          id: 2,
          content: `Duis vitae ullamcorper justo, quis sollicitudin eros. Quisque sed elit ligula. Maecenas faucibus nulla augue, sit amet condimentum ante finibus vitae. Morbi dignissim lacinia pharetra. `,
          author: {
            name: `Test`,
            image: {
              default: `/images/temoignages/portraits/judith.png`,
              x2: `/images/temoignages/portraits/judith@2x.png`,
            },
          },
          organization: {
            name: 'Singa',
            logo: {
              default: `/images/temoignages/organisations/singa.png`,
              x2: `/images/temoignages/organisations/singa@2x.png`,
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
