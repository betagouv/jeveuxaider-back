<template>
  <div>
    <VueSlickCarousel ref="vueSlickCarousel" v-bind="settings">
      <nuxt-link
        v-for="mission in missions"
        :key="mission.id"
        class="card--mission--wrapper"
        :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
      >
        <CardMission :mission="mission" class="!h-full" />
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
            v-show="arrowOption.currentSlide < missions.length - 1"
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
        <div class="sm:flex items-center sm:space-x-8 text-base text-[#DEDEDE]">
          <div>●</div>
        </div>
      </template>
    </VueSlickCarousel>

    <nuxt-link
      v-if="moreLink"
      ref="moreLink"
      :to="moreLink"
      class="text-[#696974] hover:underline"
    >
      Plus de missions ›
    </nuxt-link>
  </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel'
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'

export default {
  components: { VueSlickCarousel },
  props: {
    missions: {
      type: Array,
      required: true,
    },
    moreLink: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
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
      wrapper.className =
        'mt-6 flex flex-col sm:flex-row items-center sm:justify-start space-y-4 sm:space-y-0 sm:space-x-8'
      dotsWrapper.parentNode.insertBefore(wrapper, dotsWrapper)
      wrapper.appendChild(dotsWrapper)

      const moreLinkEl = this.$refs?.moreLink?.$el
      if (moreLinkEl) {
        wrapper.appendChild(moreLinkEl)
      }
    }
  },
}
</script>

<style lang="postcss" scoped>
.card--mission--wrapper {
  @apply flex flex-col h-full;
  width: calc(100vw - 64px) !important;
  @apply max-w-[325px] sm:w-[325px];
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
      > div > div {
        height: 100%;
      }
    }

    .slick-arrow {
      box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.15);
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
