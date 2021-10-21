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
            width="790"
            height="670"
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
    <section
      v-if="prioritizedMissions.length > 0"
      class="overflow-hidden xl:mt-6"
    >
      <div class="container mx-auto px-8 sm:px-4">
        <div class="flex justify-between items-baseline mb-6">
          <h2
            class="text-4xl lg:text-5xl tracking-tighter text-center md:text-left"
          >
            Les missions de bénévolat
            <strong class="font-extrabold">prioritaires</strong>
          </h2>
          <span
            class="hidden lg:block ml-4 text-[32px] xl:text-[40px] text-[#A7A7B0] font-light"
          >
            #{{ Date.now() | formatCustom('MMMM') }}
          </span>
        </div>

        <SlideshowMissions
          :missions="prioritizedMissions"
          more-link="/missions-benevolat?toggle[is_priority]=true"
        />
      </div>
    </section>

    <!-- DOMAINES D'ACTION -->
    <section class="mt-16 py-16 bg-white overflow-hidden">
      <div class="container mx-auto px-8 sm:px-4">
        <div class="flex justify-between items-baseline mb-6">
          <h2
            class="text-4xl lg:text-5xl tracking-tighter text-center md:text-left"
          >
            Trouvez votre
            <strong class="font-extrabold">domaine d'action</strong>
          </h2>
          <span
            class="hidden md:block ml-4 text-[32px] xl:text-[40px] text-[#A7A7B0] font-light"
          >
            #jeveuxaider
          </span>
        </div>

        <SlideshowDomaines />
      </div>
    </section>

    <!-- ENGAGEZ-VOUS -->
    <section class="py-16 bg-[#F9F8F6] overflow-hidden">
      <div class="container mx-auto px-4 relative">
        <h2
          class="text-4xl lg:text-5xl tracking-tighter text-center lg:text-left"
        >
          Engagez-vous
          <strong class="font-extrabold">près de chez vous</strong>
        </h2>

        <div
          class="mt-8 max-w-3xl lg:max-w-[500px] mx-auto lg:ml-0 flex flex-wrap gap-4 items-center justify-center lg:justify-start"
        >
          <nuxt-link
            v-for="(city, index) in hightlightedCities"
            :key="city.name"
            class="text-[#696974] leading-none truncate px-[18px] h-[40px] flex items-center rounded-full text-[13px] shadow-md font-extrabold tracking-wide uppercase bg-white transform transition will-change-transform hover:scale-110"
            :class="[
              {
                'w-[40px] h-[40px] !p-0 flex items-center justify-center text-[26px] font-normal':
                  index == hightlightedCities.length - 1,
              },
            ]"
            :to="city.url"
          >
            <template v-if="index != hightlightedCities.length - 1">
              {{ city.name }}
            </template>

            <img v-else src="/images/more.svg" alt="Voir plus de villes" />
          </nuxt-link>
        </div>

        <div
          class="bg-white mt-16 text-center md:text-left rounded-[10px] overflow-hidden lg:max-w-[660px]"
          style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05)"
        >
          <div class="flex flex-col items-center md:flex-row md:items-end">
            <div class="px-6 pt-8 md:pb-8 md:px-8">
              <h2 class="text-4xl lg:text-5xl tracking-tighter">
                Ou <strong class="font-extrabold">à distance</strong>
              </h2>

              <div class="mt-4 text-xl text-[#696974]">
                Plus de 1 000 missions de bénévolat sont réalisables en
                autonomie
              </div>

              <nuxt-link
                class="rounded-full text-white bg-jva-green hover:scale-105 !outline-none focus:scale-105 transition px-8 py-3 transform will-change-transform shadow-xl font-bold inline-flex mt-8"
                to="/missions-benevolat?refinementList[type][0]=Mission à distance"
              >
                <span>Découvrir le télébénévolat</span>
              </nuxt-link>
            </div>

            <img
              src="/images/telebenevolat.svg"
              alt="Télébénévolat"
              class="-mt-16 md:mt-0 md:-mr-24"
              width="379"
              height="292"
            />
          </div>
        </div>

        <img
          src="/images/map_france_2.svg"
          alt="Carte de la France"
          class="hidden lg:block absolute top-0 right-0 mr-[-150px] xl:-mr-0 pr-4"
          width="607"
          height="624"
          style="filter: drop-shadow(8px 20px 16px rgba(0, 0, 0, 0.1))"
        />
      </div>
    </section>

    <!-- ACTUALITÉS -->
    <section v-if="articles.length > 0" class="py-16 bg-white overflow-hidden">
      <div class="container mx-auto px-8 sm:px-4">
        <div class="flex justify-between items-baseline mb-6">
          <h2
            class="text-4xl lg:text-5xl tracking-tighter text-center md:text-left"
          >
            Les actualités de
            <span>l'<strong class="font-extrabold">engagement</strong></span>
          </h2>
          <span
            class="hidden md:block ml-4 text-[32px] xl:text-[40px] text-[#A7A7B0] font-light"
          >
            #blog
          </span>
        </div>

        <SlideshowArticles :articles="articles" />
      </div>
    </section>

    <!-- TÉMOIGNAGES -->
    <section class="py-16 bg-[#F9F8F6] overflow-hidden">
      <div class="container mx-auto px-8 sm:px-4">
        <h2
          class="text-4xl lg:text-5xl tracking-tighter text-center text-[#AFAFAE]"
        >
          Paroles de
          <strong class="font-extrabold">bénévoles</strong>
        </h2>

        <SlideshowTestimonies class="mt-12" />
      </div>
    </section>

    <div class="container mx-auto px-4 mt-8">
      <div class="bg-red-500 p-12"></div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      prioritizedMissions: [],
      articles: [],
      hightlightedCities: [
        {
          name: 'Paris',
          url: '/villes/paris',
        },
        {
          name: 'Toulouse',
          url: '/villes/toulouse',
        },
        {
          name: 'Lyon',
          url: '/villes/lyon',
        },
        {
          name: 'Marseille',
          url: '/villes/marseille',
        },
        {
          name: 'Bordeaux',
          url: '/villes/bordeaux',
        },
        {
          name: 'Lille',
          url: '/villes/lille',
        },
        {
          name: 'Rennes',
          url: '/villes/rennes',
        },
        {
          name: 'Montpellier',
          url: '/villes/montpellier',
        },
        {
          name: 'Strasbourg',
          url: '/villes/strasbourg',
        },
        {
          name: 'Nice',
          url: '/villes/nice',
        },
        {
          name: 'Rouen',
          url: '/villes/rouen',
        },
        {
          name: 'Angers',
          url: '/villes/angers',
        },
        {
          name: '+',
          url: '/territoires',
        },
      ],
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchMissions({
      'filter[is_priority]': 'true',
      'filter[state]': 'Validée',
      'filter[structure.state]': 'Validée',
    })
    this.prioritizedMissions = data.data

    const { data: articles } = await this.$axios.get(
      `${this.$config.blog.restApiUrl}/posts/?per_page=6`,
      {
        excludeContextRole: true,
      }
    )
    const articlesWithMedia = []
    for (const article of articles) {
      const url = article._links['wp:featuredmedia']
        ? article._links['wp:featuredmedia'][0].href
        : article._links['wp:attachment'][0].href
      const { data: media } = await this.$axios.get(url, {
        excludeContextRole: true,
      })

      if (!Array.isArray(media)) {
        articlesWithMedia.push({ ...article, media })
      } else {
        articlesWithMedia.push({ ...article, media: media[0] })
      }
    }
    this.articles = articlesWithMedia
  },
  head() {
    return {
      title:
        'Je Veux Aider | Devenez bénévole dans une association en quelques clics | La plateforme publique du bénévolat par la Réserve Civique',
      link: [
        {
          rel: 'canonical',
          href: 'https://www.jeveuxaider.gouv.fr/',
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            "Trouvez une mission de bénévolat dans une association, organisation publique ou une commune, partout en France, sur le terrain ou à distance. 50 000 places disponibles dans 10 domaines d'action : solidarité, insertion, éducation, environnement, santé, sport, culture ...",
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
        {
          hid: 'facebook-domain-verification',
          name: 'facebook-domain-verification',
          content: '8jnmyx2s1iopvryhthxappg6b3tryp',
        },
      ],
    }
  },
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
