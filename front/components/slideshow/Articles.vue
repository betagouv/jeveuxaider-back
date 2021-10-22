<template>
  <Slideshow
    :slides-are-links="true"
    :more-link="{
      link: `https://jeveuxaider.gouv.fr/engagement/actualites/`,
      label: `Plus d'articles ›`,
      isExternal: true,
    }"
    :slides-count="articles.length"
  >
    <a
      v-for="article in articles"
      :key="article.id"
      class="card--article--wrapper"
      :href="`${$config.blog.url}/${article.slug}`"
      target="_blank"
    >
      <CardArticle :article="article" class="!h-full" />
    </a>
  </Slideshow>
</template>

<script>
import Slideshow from '@/components/Slideshow'

export default {
  components: { Slideshow },
  props: {
    articles: {
      type: Array,
      required: true,
    },
  },
}
</script>

<style lang="postcss" scoped>
.card--article--wrapper {
  @apply !flex flex-col h-full max-w-[325px] transition rounded-[10px];
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
