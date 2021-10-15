<template>
  <div>
    <!-- BANNER -->
    <section class="relative h-[590px]">
      <picture class="lg:hidden">
        <source
          srcset="/images/banner.jpg, /images/banner@2x.jpg 2x"
          media="(min-width: 440px)"
        />
        <img
          srcset="/images/banner_mobile.jpg, /images/banner_mobile@2x.jpg 2x"
          alt="Récolte de nourriture et de vêtement"
          class="object-cover absolute w-full h-full"
        />
      </picture>

      <video
        autoplay
        loop
        muted
        class="object-cover absolute w-full h-full hidden lg:block"
      >
        <source src="/video/banner.mp4" type="video/mp4" />
      </video>

      <div class="bg-black opacity-50 absolute inset-0 h-full w-full"></div>

      <div class="relative z-10 h-full">
        <div class="container mx-auto px-4 text-center text-white h-full">
          <div class="flex flex-col justify-center items-center h-full">
            <div
              class="text-[50px] leading-none lg:text-7xl font-extrabold mb-4 tracking-tighter"
            >
              Envie de bénévolat&nbsp;?
            </div>

            <h1 class="text-2xl lg:text-[28px] lg:leading-[34px] mb-8">
              <strong>Devenez bénévole</strong>
              <span>et trouvez des missions de bénévolat</span>
              <br class="hidden md:block" />
              <strong>près de chez vous</strong>
              <span>ou</span>
              <strong>à distance</strong>
            </h1>

            <button
              class="flex items-center justify-center rounded-full text-white bg-jva-green hover:scale-105 !outline-none focus:scale-105 transition px-8 py-4 transform will-change-transform shadow-xl font-bold text-xl"
              @click="handleClickCTA()"
            >
              <img
                class="flex-none"
                src="/images/search.svg"
                alt="Rechercher"
                width="16"
                height="16"
              />
              <span class="ml-2">Trouver une mission</span>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- ORGANISATIONS -->
    <section class="relative overflow-hidden md:h-[500px] md:flex items-center">
      <div class="md:container md:mx-auto">
        <picture>
          <source
            srcset="
              /images/mosaique_orgas_desktop.png,
              /images/mosaique_orgas_desktop@2x.png 2x
            "
            media="(min-width: 440px)"
          />
          <img
            srcset="/images/mosaique_orgas@2x.png 2x"
            alt="Mosaïque organisations"
            class="object-cover object-left-bottom w-full h-[370px] md:h-[650px] translate-y-[-45px] md:translate-y-[-138px] md:absolute md:right-0 md:top-0 mosaic"
          />
        </picture>

        <div
          class="max-w-[440px] mx-auto md:ml-0 px-4 text-center md:text-left"
        >
          <h2 class="font-extrabold text-4xl lg:text-5xl tracking-tighter mb-6">
            Chacun pour tous
          </h2>
          <p class="text-[#696974] text-2xl mb-6">
            Plus de <strong>60 000 missions de bénévolat</strong> sont
            disponibles chez les petits et grands acteurs de l'engagement.
          </p>
          <a
            href="https://www.jeveuxaider.gouv.fr/engagement/organisations/"
            class="text-lg text-primary hover:underline"
          >
            Consulter toutes les organisations ›
          </a>
        </div>
      </div>
    </section>

    <hr class="mt-12 pt-12 md:hidden border-[#CDCDCD]" />

    <!-- MISSIONS PRIORITAIRES -->
    <section>
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-baseline mb-6">
          <h2
            class="text-4xl lg:text-5xl tracking-tighter text-center md:text-left"
          >
            Les missions de bénévolat
            <strong class="font-extrabold">prioritaires</strong>
          </h2>
          <span
            class="hidden md:block ml-4 text-[32px] xl:text-[40px] text-[#A7A7B0] font-light"
          >
            #{{ Date.now() | formatCustom('MMMM') }}
          </span>
        </div>
      </div>
    </section>

    <div class="container mx-auto px-4">
      <div class="bg-red-500 p-12"></div>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    handleClickCTA() {
      window.plausible &&
        window.plausible('Click CTA - Homepage', {
          props: { isLogged: this.$store.getters.isLogged },
        })
      this.$store.commit('toggleSearchOverlay')
    },
  },
}
</script>

<style lang="postcss" scoped>
.mosaic {
  @screen md {
    width: calc(50% - 10px);
  }

  @screen lg {
    width: calc(50% + 50px);
  }

  @screen lg {
    width: 50%;
  }
}
</style>
