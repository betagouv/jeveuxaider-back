<template>
  <div>
    <div class="text-center mb-6">
      <div
        class="
          text-gray-900
          font-extrabold
          text-2xl
          lg:text-3xl
          leading-8
          mb-2
          lg:mb-3
        "
      >
        Votre candidature est enregistrée
      </div>
      <div
        class="text-gray-500 font-semibold text-lg lg:text-xl max-w-md mx-auto"
      >
        Et si vous invitiez vos proches à participer eux aussi à cette mission ?
      </div>
    </div>
    <div class="">
      <div class="text-xl font-semibold text-center">
        Partagez la mission autour de vous
      </div>
      <div class="flex justify-center space-x-3 my-10">
        <a
          target="_blank"
          :href="`https://www.facebook.com/sharer/sharer.php?u=${baseUrl}${$router.currentRoute.fullPath}`"
          class="
            input-shadow
            bg-white
            h-12
            w-12
            lg:h-24
            lg:w-24
            rounded-full
            flex
            justify-center
            items-center
            cursor-pointer
            tracking-wide
            shadow-lg
            hover:scale-105
            transform
            transition
            duration-150
            ease-in-out
            will-change-transform
          "
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
          class="
            input-shadow
            bg-white
            h-12
            w-12
            lg:h-24
            lg:w-24
            rounded-full
            flex
            justify-center
            items-center
            cursor-pointer
            tracking-wide
            shadow-lg
            hover:scale-105
            transform
            transition
            duration-150
            ease-in-out
            will-change-transform
          "
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
          class="
            input-shadow
            bg-white
            h-12
            w-12
            lg:h-24
            lg:w-24
            rounded-full
            flex
            justify-center
            items-center
            cursor-pointer
            tracking-wide
            shadow-lg
            hover:scale-105
            transform
            transition
            duration-150
            ease-in-out
            will-change-transform
          "
        >
          <img
            src="@/assets/images/share-linkedin.svg"
            alt="Partagez la mission sur Linkedin"
            class="w-6 h-auto lg:w-auto"
          />
        </a>
        <a
          :href="`mailto:?&subject=${$store.getters.missionSelected.name}&body=${message}${baseUrl}${$router.currentRoute.fullPath}`"
          class="
            input-shadow
            bg-white
            h-12
            w-12
            lg:h-24
            lg:w-24
            rounded-full
            flex
            justify-center
            items-center
            cursor-pointer
            tracking-wide
            shadow-lg
            hover:scale-105
            transform
            transition
            duration-150
            ease-in-out
            will-change-transform
          "
        >
          <img
            src="@/assets/images/share-mail.svg"
            alt="Partagez la mission par mail"
            class="w-6 h-auto lg:w-auto"
          />
        </a>
      </div>
      <el-button
        class="
          font-bold
          text-gray-500
          max-w-sm
          mx-auto
          w-full
          flex
          items-center
          justify-center
          px-5
          py-3
          border border-transparent
          text-xl
          leading-6
          rounded-full
          bg-white
          focus:outline-none
          focus:shadow-outline
          shadow-lg
          hover:scale-105
          transform
          transition
          duration-150
          ease-in-out
          will-change-transform
          hover:text-blue-800
        "
        @click.prevent="onSkip"
      >
        Passer
      </el-button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SoftGateShare',
  data() {
    return {
      message:
        "J'ai trouvé ma future mission de bénévolat sur JeVeuxAider. Rejoignez le mouvement %23ChacunPourTous ",
      baseUrl: this.$config.appUrl,
    }
  },
  methods: {
    onSkip() {
      this.$emit('next')
    },
  },
}
</script>

<style lang="sass" scoped></style>
