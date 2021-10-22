<template>
  <div>
    <VueSlickCarousel ref="vueSlickCarousel" v-bind="settings">
      <slot ref="slides"></slot>

      <template #prevArrow="arrowOption">
        <transition name="fade">
          <div
            v-show="settings.infinite || arrowOption.currentSlide"
            class="rounded-full !bg-white transition will-change-transform flex justify-center items-center !p-6 relative z-10"
          >
            <img
              src="/images/chevron_left.svg"
              alt="Précédent"
              class="absolute inset-0 m-auto"
              data-not-lazy
            />
          </div>
        </transition>
      </template>

      <template #nextArrow="arrowOption">
        <transition name="fade">
          <div
            v-show="
              settings.infinite || arrowOption.currentSlide < slidesCount - 1
            "
            class="rounded-full !bg-white transition will-change-transform flex justify-center items-center !p-6 relative z-10"
          >
            <img
              src="/images/chevron_right.svg"
              alt="Suivant"
              class="absolute inset-0 m-auto"
              data-not-lazy
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

    <template v-if="moreLink">
      <nuxt-link
        v-if="!moreLink.isExternal"
        ref="moreLink"
        slot="moreLink"
        :to="moreLink.link"
        class="text-[#696974] hover:underline"
      >
        {{ moreLink.label }}
      </nuxt-link>

      <a
        v-else
        ref="moreLink"
        :href="moreLink.link"
        target="_blank"
        class="text-[#696974] hover:underline"
      >
        {{ moreLink.label }}
      </a>
    </template>
  </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel'
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'

export default {
  components: { VueSlickCarousel },
  props: {
    slidesAreLinks: {
      type: Boolean,
      default: false,
    },
    moreLink: {
      type: [Object, Boolean],
      default: false,
    },
    slidesCount: {
      type: Number,
      required: true,
    },
    settings: {
      type: Object,
      default() {
        return {
          arrows: true,
          dots: true,
          speed: 500,
          edgeFriction: 0,
          touchThreshold: 100,
          swipeToSlide: true,
          infinite: false,
          variableWidth: true,
        }
      },
    },
  },
  mounted() {
    if (this.slidesAreLinks) {
      this.handleSlidesAccessibility()
    }
    this.handleDotsWrapper()
  },
  methods: {
    handleSlidesAccessibility() {
      const slides = this.$refs.vueSlickCarousel.$el.getElementsByClassName(
        'slick-slide'
      )
      slides.forEach((slide) => {
        slide.removeAttribute('tabindex')
        slide.getElementsByTagName('a').item(0).removeAttribute('tabindex')
      })
    },
    handleDotsWrapper() {
      const dotsWrapper = this.$refs?.vueSlickCarousel?.$el
        ?.getElementsByClassName('slick-dots')
        ?.item(0)

      if (dotsWrapper) {
        const wrapper = document.createElement('div')
        wrapper.className =
          'wrapper--slick-dots mt-6 flex flex-col sm:flex-row items-center sm:justify-start space-y-4 sm:space-y-0 sm:space-x-8'
        dotsWrapper.parentNode.insertBefore(wrapper, dotsWrapper)
        wrapper.appendChild(dotsWrapper)

        const moreLinkEl = this.$refs?.moreLink?.$el || this.$refs?.moreLink
        if (moreLinkEl) {
          wrapper.appendChild(moreLinkEl)
        }
      }
    },
  },
}
</script>

<style lang="postcss" scoped>
.slick-slider {
  ::v-deep {
    .slick-slide {
      height: auto !important;
    }

    .slick-list {
      overflow: visible;
    }

    .slick-track {
      @apply space-x-[30px] flex items-stretch;
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
      @apply transform hover:scale-125;
      &.slick-prev {
        @apply left-0;
        top: calc(50% - 24px);
        @apply -translate-y-1/2;
      }
      &.slick-next {
        @apply right-0;
        top: calc(50% - 24px);
        @apply -translate-y-1/2;
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
