<template>
  <div>
    <VueSlickCarousel ref="vueSlickCarousel" v-bind="settings">
      <div v-for="testimony in testimonies" :key="testimony.id">
        <div
          class="flex flex-col items-center space-y-6 text-center max-w-[768px] mx-auto"
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

      <template #prevArrow>
        <transition name="fade">
          <div
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

      <template #nextArrow>
        <transition name="fade">
          <div
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
        fade: true,
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
.slick-slider {
  ::v-deep {
    .slick-slide {
      height: auto !important;
    }

    .slick-arrow {
      box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.15);
      display: none;
      @screen lg {
        display: block;
        transform: translate(0px, -50%) !important;
        &.slick-prev {
          left: 0;
        }
        &.slick-next {
          right: 0;
        }
      }
    }

    .slick-dots {
      position: inherit;
      @apply text-center bottom-0 w-auto flex-none;
      > li {
        @apply w-auto h-auto m-[6px];
        &.slick-active > div {
          color: #696974;
        }
      }
    }
  }
}
</style>
