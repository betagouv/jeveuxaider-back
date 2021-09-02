<template>
  <div class="fixed inset-0 w-full h-full z-50">
    <div
      id="share-overlay"
      class="w-full h-full flex flex-col items-center justify-center"
    >
      <div class="flex flex-col w-full h-full px-4">
        <button
          class="p-4 -mr-4 lg:m-0 lg:p-8 cursor-pointer ml-auto lg:absolute lg:right-0"
          @click="onClose"
        >
          <img src="/images/close-white.svg" alt="Fermer" width="24px" />
        </button>

        <div
          v-scroll-lock="true"
          class="overflow-y-auto flex-1 flex flex-col lg:justify-center"
        >
          <div class="pb-32 lg:pb-0">
            <div class="text-center text-white text-lg">#ChacunPourTous</div>

            <div class="title text-center text-white font-extrabold mb-4">
              Partagez la mission autour de vous
            </div>

            <div class="flex justify-center space-x-3 my-10">
              <a
                target="_blank"
                :href="`https://www.facebook.com/sharer/sharer.php?u=${baseUrl}${$router.currentRoute.fullPath}`"
                :class="buttonClasses"
              >
                <img
                  src="@/assets/images/share-facebook.svg"
                  alt="Partagez la mission sur Facebook"
                  class="h-6 w-auto lg:h-auto"
                />
              </a>

              <a
                target="_blank"
                :href="`https://twitter.com/intent/tweet?url=${message}${baseUrl}${$router.currentRoute.fullPath}`"
                :class="buttonClasses"
              >
                <img
                  src="@/assets/images/share-twitter.svg"
                  alt="Partagez la mission sur Twitter"
                  class="w-6 h-auto lg:w-auto"
                />
              </a>

              <a
                target="_blank"
                :href="`https://www.linkedin.com/shareArticle?mini=true&url=${baseUrl}${$router.currentRoute.fullPath}&title=${message}`"
                :class="buttonClasses"
              >
                <img
                  src="@/assets/images/share-linkedin.svg"
                  alt="Partagez la mission sur Linkedin"
                  class="w-6 h-auto lg:w-auto"
                />
              </a>

              <a
                :href="`mailto:?&subject=${$store.getters.missionSelected.name}&body=${message}${baseUrl}${$router.currentRoute.fullPath}`"
                :class="buttonClasses"
              >
                <img
                  src="@/assets/images/share-mail.svg"
                  alt="Partagez la mission par mail"
                  class="w-6 h-auto lg:w-auto"
                />
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      baseUrl: this.$config.appUrl,
      message:
        "J'ai trouvé ma future mission de bénévolat sur JeVeuxAider. Rejoignez le mouvement %23ChacunPourTous ",
      buttonClasses:
        'bg-white h-12 w-12 lg:h-24 lg:w-24 rounded-full flex justify-center items-center cursor-pointer tracking-wide shadow-lg hover:scale-105 transform transition will-change-transform',
    }
  },
  methods: {
    onClose() {
      this.$store.commit('setMissionSelected', null)
      this.$emit('closed')
    },
  },
}
</script>

<style lang="postcss" scoped>
#share-overlay {
  background-color: rgba(25, 22, 130, 0.95);
  .title {
    font-size: 24px;
    @screen lg {
      font-size: 50px;
      letter-spacing: -1px;
    }
  }
}
</style>
