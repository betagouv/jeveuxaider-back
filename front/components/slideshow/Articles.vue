<template>
  <div>
    <VueSlickCarousel ref="vueSlickCarousel" v-bind="settings">
      <a
        v-for="article in articles"
        :key="article.id"
        class="card--article--wrapper"
        :href="`${$config.blog.url}/${article.slug}`"
        target="_blank"
      >
        <CardArticle :article="article" class="!h-full" />
      </a>

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
            v-show="arrowOption.currentSlide < articles.length - 1"
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

    <a
      ref="moreLink"
      href="https://jeveuxaider.gouv.fr/engagement/actualites/"
      target="_blank"
      class="text-[#696974] hover:underline"
    >
      Plus d'articles ›
    </a>
  </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel'
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'

export default {
  components: { VueSlickCarousel },
  props: {
    articles: {
      type: Array,
      required: true,
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
    const slickEl = this.$refs?.vueSlickCarousel?.$el
    if (slickEl) {
      const dotsWrapper = slickEl.getElementsByClassName('slick-dots').item(0)
      const wrapper = document.createElement('div')
      wrapper.className =
        'mt-6 flex flex-col sm:flex-row items-center sm:justify-start space-y-4 sm:space-y-0 sm:space-x-8'
      if (dotsWrapper) {
        dotsWrapper.parentNode.insertBefore(wrapper, dotsWrapper)
        wrapper.appendChild(dotsWrapper)
        wrapper.appendChild(this.$refs.moreLink)
      }
    }
  },
}
</script>

<style lang="postcss" scoped>
.card--article--wrapper {
  @apply flex flex-col h-full max-w-[325px];
  width: calc(100vw - 64px) !important;
  @apply w-full;
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
