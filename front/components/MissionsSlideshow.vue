<template>
  <div class="relative mx-auto">
    <Swiper :options="swiperOptions" class="p-1" style="max-width: 940px">
      <SwiperSlide v-for="mission in missions" :key="mission.id">
        <nuxt-link
          class="
            card--mission--wrapper
            focus:outline-none
            focus:shadow-outline
            rounded-lg
          "
          :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
        >
          <CardMission :mission="mission" />
        </nuxt-link>
      </SwiperSlide>
    </Swiper>

    <div slot="button-prev" class="swiper-button-prev hidden lg:flex"></div>
    <div slot="button-next" class="swiper-button-next hidden lg:flex"></div>
    <div
      slot="pagination"
      class="swiper-pagination w-full mt-2 lg:hidden"
    ></div>
  </div>
</template>

<script>
export default {
  props: {
    missions: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      swiperOptions: {
        pagination: {
          el: '.swiper-pagination',
          type: 'bullets',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3,
          },
        },
        spaceBetween: 20,
        centerInsufficientSlides: true,
        keyboard: {
          enabled: true,
        },
      },
    }
  },
  computed: {
    swiper() {
      return this.$refs.mySwiper.$swiper
    },
  },
}
</script>

<style lang="sass" scoped>
.card--mission--wrapper
  @apply flex flex-col h-full
.swiper-slide
  height: auto !important

.swiper-button-next,
.swiper-button-prev
  @apply text-white rounded-full border w-6 h-6 transition ease-in-out duration-150 opacity-50
  &::after
    @apply text-xs
  &:hover,
  &:focus
    @apply opacity-100
  &:focus
    @apply shadow-outline outline-none
  &.swiper-button-disabled
    @apply opacity-0

.swiper-button-next
  right: -14px
  @screen xl
    right: 110px
  &::after
    margin-left: 1px

.swiper-button-prev
  left: -14px
  @screen xl
    left: 110px

.swiper-pagination
  &::v-deep .swiper-pagination-bullet
    background: white
    margin: 4px
</style>
