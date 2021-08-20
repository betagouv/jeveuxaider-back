<template>
  <VueSlickCarousel v-bind="settings" class="m-auto" style="max-width: 960px">
    <div v-for="mission in missions" :key="mission.id">
      <nuxt-link
        class="card--mission--wrapper !outline-none focus:ring rounded-lg mx-2.5"
        :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
      >
        <CardMission :mission="mission" />
      </nuxt-link>
    </div>
  </VueSlickCarousel>
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
  },
  data() {
    return {
      settings: {
        arrows: true,
        dots: false,
        speed: 500,
        slidesToShow: 3,
        touchThreshold: 5,
        centerPadding: '20px',
        infinite: false,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 2,
            },
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
              arrows: false,
            },
          },
        ],
      },
    }
  },
}
</script>

<style lang="postcss" scoped>
.card--mission--wrapper {
  @apply flex flex-col h-full;
}
.slick-slide {
  height: auto !important;
}
</style>
