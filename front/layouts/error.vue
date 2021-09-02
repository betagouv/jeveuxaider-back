<template>
  <div class="relative flex-1 flex flex-col">
    <div class="absolute inset-0" style="background-color: #191682">
      <img
        class="z-1 object-cover absolute h-full"
        alt="Je Veux Aider"
        :srcSet="bgHeroMultipleSizes.srcSet"
        :src="bgHeroMultipleSizes.src"
        width="100%"
        height="100%"
      />
    </div>

    <div class="relative m-auto">
      <div class="px-4 py-8 lg:p-12 xl:p-24 text-white text-center">
        <template v-if="error.statusCode == 404">
          <h2 id="titre-error" class="font-bold text-3xl lg:text-5xl">
            Oups, cette page est introuvable&nbsp;…
          </h2>

          <p class="text-xl lg:text-2xl mt-4 mb-8 lg:my-8">
            À priori cette page n'existe plus. <br class="hidden sm:block" />
            Ou pire, elle peut ne jamais avoir existée&nbsp;…
          </p>
        </template>

        <template v-else-if="error.statusCode == 403">
          <h2 id="titre-error" class="font-bold text-3xl lg:text-5xl">
            Oups, ce contenu ne vous est pas destiné&nbsp;…
          </h2>

          <p class="text-xl lg:text-2xl mt-4 mb-8 lg:my-8">
            À priori vous n'êtes pas autorisé à accéder à cette page.
          </p>
        </template>

        <template v-else>
          <h2 id="titre-error" class="font-bold text-3xl lg:text-5xl">
            Erreur {{ error.statusCode }}
          </h2>

          <p class="text-xl lg:text-2xl mt-4 mb-8 lg:my-8">
            {{ error.message }}
          </p>
        </template>

        <div class="flex flex-col items-center">
          <nuxt-link
            to="/"
            class="rounded-full bg-white text-[#070191] font-extrabold py-4 px-12 text-xl hover:scale-105 transform transition inline-block"
          >
            Revenir en lieu sûr
          </nuxt-link>

          <a
            class="underline hover:no-underline mt-4 text-lg cursor-pointer"
            @click.prevent="back()"
            >Page précédente</a
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const bgHeroMultipleSizes = require('@/assets/images/bg-error.png?resize&sizes[]=320&sizes[]=640&sizes[]=960&sizes[]=1200&sizes[]=1800&sizes[]=2400&sizes[]=3900')

export default {
  layout: 'error-layout',
  props: {
    error: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      bgHeroMultipleSizes,
    }
  },
  methods: {
    back() {
      window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
    },
  },
}
</script>

<style lang="postcss" scoped>
#titre-error {
  letter-spacing: -1px;
  @screen lg {
    letter-spacing: -2px;
  }
}
</style>
