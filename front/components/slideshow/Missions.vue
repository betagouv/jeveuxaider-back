<template>
  <Slideshow
    :slides-are-links="true"
    :more-link="
      moreLink
        ? {
            link: moreLink,
            label: 'Plus de missions ›',
          }
        : false
    "
    :slides-count="missions.length"
  >
    <nuxt-link
      v-for="mission in missions"
      :key="mission.id"
      class="card--mission--wrapper"
      :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
    >
      <CardMission :mission="mission" class="!h-full" />
    </nuxt-link>
  </Slideshow>
</template>

<script>
import Slideshow from '@/components/Slideshow'

export default {
  components: { Slideshow },
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
}
</script>

<style lang="postcss" scoped>
.card--mission--wrapper {
  @apply !flex flex-col h-full max-w-[323px] rounded-[10px] transition;
  width: calc(100vw - 64px) !important;
  @apply w-full;
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
}
</style>
