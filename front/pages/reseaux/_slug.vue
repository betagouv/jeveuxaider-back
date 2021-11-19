<template>
  <div>
    <nuxt-link
      v-if="$store.getters.contextRole === 'admin'"
      :to="`/dashboard/reseaux/${reseau.id}/edit`"
      class="fixed bottom-0 p-2 z-50 bg-white rounded-full m-4 shadow-lg hover:shadow-2xl border"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-5 w-5"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
        /></svg
    ></nuxt-link>

    <!-- ROW 1 -->
    <div class="relative bg-white md:grid md:grid-cols-3 lg:grid-cols-2">
      <!-- 1 -- LEFT -->
      <div class="col-span-2 lg:col-span-1">
        <header class="border-b flex justify-between items-stretch">
          <div class="p-4 border-r flex items-center">
            <img src="/images/logo_arianne.svg" width="55" class="my-auto" />
          </div>

          <div class="p-4 flex items-center">
            <span class="text-xs uppercase text-gray-500 mr-3"
              >Encouragé par</span
            >
            <nuxt-link to="/">
              <img
                src="@/assets/images/jeveuxaider-logo.svg"
                alt="Bénévolat je veux aider"
                title="Bénévolat association"
                width="140"
              />
            </nuxt-link>
          </div>
        </header>

        <div class="px-4 max-w-3xl ml-auto">
          <div class="pb-4 md:p-8 lg:pt-6 xl:p-16 xl:pt-8">
            <Breadcrumb class="breadcrumb" :items="[{ label: reseau.name }]" />

            <img
              v-if="reseau.logo"
              :srcset="reseau.logo.large"
              :alt="reseau.name"
              class="my-8 h-auto"
              style="max-width: 16rem; max-height: 10rem"
            />

            <h1
              class="mt-2 text-3xl sm:text-5xl font-bold tracking-tighter text-gray-900"
            >
              <div>Découvrez l'organisation</div>
              <span class="font-extrabold">{{ reseau.name }}</span>
            </h1>

            <div
              class="h-[2.5px] w-16 my-10"
              :style="`background-color: ${color}`"
            ></div>

            <client-only :placeholder="reseau.description">
              <v-clamp
                tag="div"
                :max-lines="5"
                autoresize
                class="text-gray-500 text-lg"
                :expanded="expandDescription"
              >
                {{ reseau.description }}

                <template slot="after" slot-scope="{ clamped }">
                  <span
                    v-if="clamped"
                    class="uppercase text-black text-sm font-semibold cursor-pointer"
                    @click="expandDescription = true"
                  >
                    Lire plus</span
                  >
                </template>
              </v-clamp>
            </client-only>
          </div>
        </div>

        <footer
          class="grid grid-cols-3 divide-x divide-gray-200 text-center border-t"
        >
          <button v-scroll-to="'#missions'" class="footer--button">
            Devenir bénévole
          </button>

          <button
            v-if="reseau.donation"
            v-scroll-to="'#faire-un-don'"
            class="footer--button"
          >
            Faire un don
          </button>

          <button v-scroll-to="'#infos'" class="footer--button">
            Infos pratiques
          </button>
        </footer>
      </div>

      <!-- 1 -- RIGHT -->
      <div>
        <img
          :srcset="image1"
          class="md:absolute object-cover w-full md:w-1/3 lg:w-1/2 h-full"
        />
      </div>
    </div>

    <!-- ROW 2 -->
    <div
      class="flex flex-col md:grid md:grid-cols-3 lg:grid-cols-2 relative bg-white"
    >
      <!-- 2 -- LEFT -->
      <div class="order-2 md:order-1">
        <img
          :srcset="image2"
          class="md:absolute object-cover w-full md:w-1/3 lg:w-1/2 h-full"
        />
      </div>

      <!-- 2 -- RIGHT -->
      <div
        class="order-1 md:order-2 col-span-2 lg:col-span-1"
        :style="`background-color: ${color}`"
      >
        <div class="max-w-3xl mr-auto">
          <div class="text-white px-4 py-8 md:p-8 xl:p-16">
            <!-- TODO -->
            <div v-if="reseau.places_left" class="font-extrabold text-4xl mb-4">
              {{ reseau.places_left | formatNumber }}
              {{
                reseau.places_left
                  | pluralize(['bénévole recherché', 'bénévoles recherchés'])
              }}
            </div>

            <!-- DOMAINES -->
            <template v-if="reseau.domaines">
              <div
                class="text-2xl sm:text-4xl font-extrabold text-white mb-8 tracking-tight"
              >
                XXX bénévoles recherchés partout en France
              </div>

              <div class="flex items-center mb-4">
                <div class="flex-none uppercase font-bold text-sm mr-4">
                  Causes défendues
                </div>
                <hr class="w-full border-white opacity-25" />
              </div>

              <div v-if="!reseau.domaines_with_image.length">Non renseigné</div>

              <div v-else class="grid lg:grid-cols-2 gap-3 xl:gap-x-6">
                <div
                  v-for="domaine in reseau.domaines_with_image"
                  :key="domaine.id"
                  class="flex items-start"
                >
                  <div class="flex-none w-6 h-6 mr-3">
                    <img
                      :src="domaine.image"
                      :alt="domaine.name.fr"
                      width="24"
                      height="24"
                    />
                  </div>
                  <div class="">{{ domaine.name.fr }}</div>
                </div>
              </div>
            </template>

            <!-- PUBLICS -->
            <template v-if="reseau.publics_beneficiaires">
              <div class="flex items-center mb-4 mt-8">
                <div class="flex-none uppercase font-bold text-sm mr-4">
                  Bénéficiaires
                </div>
                <hr class="w-full border-white opacity-25" />
              </div>

              <div v-if="!reseau.publics_beneficiaires.length">
                Non renseigné
              </div>

              <div
                v-for="(
                  public_beneficiaire, key
                ) in reseau.publics_beneficiaires"
                v-else
                :key="key"
                class="flex items-center mb-3"
              >
                <div
                  class="public-wrapper w-6 h-6 mr-3 flex items-center justify-center"
                  v-html="iconPublicType(public_beneficiaire)"
                />

                <div>
                  {{
                    public_beneficiaire
                      | labelFromValue('mission_publics_beneficiaires')
                  }}
                </div>
              </div>
            </template>

            <!-- SHARE -->
            <template
              v-if="
                reseau.website ||
                reseau.facebook ||
                reseau.twitter ||
                reseau.instagram
              "
            >
              <div class="flex items-center mb-4 mt-8">
                <div class="flex-none uppercase font-bold text-sm mr-4">
                  En savoir plus
                </div>
                <hr class="w-full border-white opacity-25" />
              </div>

              <div class="flex -m-1">
                <!-- STRUCTURE LINK -->
                <button
                  v-if="reseau.website"
                  class="m-1 hover:scale-110 transform transition will-change-transform !outline-none focus-within:ring border border-white rounded-full w-10 h-10 flex items-center justify-center"
                  @click="goTo(reseau.website)"
                >
                  <img
                    class="flex-none"
                    src="/images/link.svg"
                    :alt="reseau.name"
                  />
                </button>

                <!-- FACEBOOK -->
                <button
                  v-if="reseau.facebook"
                  class="m-1 hover:scale-110 transform transition will-change-transform !outline-none focus-within:ring border border-white rounded-full w-10 h-10 flex items-center justify-center"
                  @click="goTo(reseau.facebook)"
                >
                  <img
                    class="flex-none"
                    src="/images/facebook.svg"
                    alt="Facebook"
                  />
                </button>

                <!-- TWITTER -->
                <button
                  v-if="reseau.twitter"
                  class="m-1 hover:scale-110 transform transition will-change-transform !outline-none focus-within:ring border border-white rounded-full w-10 h-10 flex items-center justify-center"
                  @click="goTo(reseau.twitter)"
                >
                  <img
                    class="flex-none"
                    src="/images/twitter_white_2.svg"
                    alt="twitter"
                  />
                </button>

                <!-- INSTAGRAM -->
                <button
                  v-if="reseau.instagram"
                  class="m-1 hover:scale-110 transform transition will-change-transform !outline-none focus-within:ring border border-white rounded-full w-10 h-10 flex items-center justify-center"
                  @click="goTo(reseau.instagram)"
                >
                  <img
                    class="flex-none"
                    src="/images/instagram.svg"
                    alt="instagram"
                  />
                </button>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>

    <!-- ANTENNES -->
    <div id="antennes" class="pt-16 pb-32">
      <div class="container px-4 mx-auto">
        <h2
          class="text-center mb-12 text-3xl sm:text-5xl tracking-tight text-gray-900 tracking-tighter max-w-3xl mx-auto"
        >
          <span>Les antennes de</span>
          <span class="font-extrabold">{{ reseau.name }}</span>
        </h2>
      </div>
    </div>
  </div>
</template>

<script>
// TODO REFACTORING AVEC ORGANISATIONS

export default {
  layout: 'organisation',
  async asyncData({ $api, params, error }) {
    const reseau = await $api.getReseau(params.slug)
    if (!reseau || !reseau.is_published) {
      return error({ statusCode: 404 })
    }

    return {
      reseau,
    }
  },
  data() {
    return {
      expandDescription: false,
    }
  },
  head() {
    return {
      title: `Découvrez l'organisation ${this.reseau.name} | JeVeuxAider.gouv.fr`,
      link: [
        {
          rel: 'canonical',
          href: `https://www.jeveuxaider.gouv.fr${this.reseau.full_url}`,
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.reseau.description
            ? this.reseau.description
                .replace(/<\/?[^>]+>/gi, ' ')
                .substring(0, 300)
            : '',
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
      ],
    }
  },
  computed: {
    image1() {
      return (
        this.reseau?.override_image_1?.xxl ??
        `/images/organisations/domaines/${this.reseau.image_1}.jpg, /images/organisations/domaines/${this.reseau.image_1}@2x.jpg 2x`
      )
    },
    image2() {
      return (
        this.reseau?.override_image_2?.xxl ??
        `/images/organisations/domaines/${this.reseau.image_2}.jpg, /images/organisations/domaines/${this.reseau.image_2}@2x.jpg 2x`
      )
    },
    color() {
      return this.reseau.color ?? '#B91C1C'
    },
  },
  methods: {
    iconPublicType(publicType) {
      let icon
      switch (publicType) {
        case 'seniors':
          icon = require('@/assets/images/icones/personnes_agees.svg?raw')
          break
        case 'persons_with_disabilities':
          icon = require('@/assets/images/icones/handicap.svg?raw')
          break
        case 'people_in_difficulty':
          icon = require('@/assets/images/icones/helping_hand.svg?raw')
          break
        case 'parents':
          icon = require('@/assets/images/icones/parents.svg?raw')
          break
        case 'children':
          icon = require('@/assets/images/icones/jeunes_enfants.svg?raw')
          break
        case 'any_public':
          icon = require('@/assets/images/icones/tous_public.svg?raw')
          break
      }

      return icon
    },
    goTo(url) {
      window.open(url, '_blank')
    },
  },
}
</script>

<style lang="postcss" scoped>
* {
  @apply border-gray-200;
}

.breadcrumb {
  border-bottom: 0 !important;
  ::v-deep ol {
    @apply px-0 !important;
  }
}

.footer--button {
  font-size: 10px;
  @apply font-bold uppercase py-6 outline-none transition-colors ease-in-out duration-200;
  &:focus-visible,
  &:hover {
    @apply bg-gray-100;
  }
  @screen sm {
    @apply text-sm;
  }
}
</style>
