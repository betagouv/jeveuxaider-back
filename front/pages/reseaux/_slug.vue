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

        <div class="max-w-3xl ml-auto">
          <div class="px-4 pb-8 md:p-8 lg:pt-6 xl:p-16 xl:pt-8">
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
          <button v-scroll-to="'#antennes'" class="footer--button">
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
                {{ reseau.participations_max | formatNumber }}
                {{
                  reseau.participations_max
                    | pluralize([
                      'bénévole recherché partout en France',
                      'bénévoles recherchés partout en France',
                    ])
                }}
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
    <section id="antennes" class="pt-16 pb-8">
      <div class="container px-4 mx-auto">
        <h2
          class="text-center mb-12 text-3xl sm:text-5xl text-gray-900 tracking-tighter max-w-3xl mx-auto"
        >
          <span>Les antennes de</span>
          <span class="font-extrabold">{{ reseau.name }}</span>
        </h2>

        <div
          class="mt-12 max-w-5xl mx-auto flex flex-wrap gap-4 items-center justify-center"
        >
          <a
            v-for="antenne in reseau.structures"
            :key="antenne.name"
            class="text-[#696974] leading-none truncate px-[18px] h-[40px] flex items-center rounded-full text-[13px] shadow-md font-extrabold tracking-wide uppercase bg-white transform transition will-change-transform hover:scale-105"
            :href="`#${antenne.id}`"
          >
            {{ antenne.city }}
          </a>

          <nuxt-link
            v-if="reseau.structures_count - 5 > 0"
            :to="`/missions-benevolat?refinementList[structure.reseau.name][0]=${reseau.name}`"
            class="text-[#696974] leading-none truncate px-[18px] h-[40px] flex items-center rounded-full text-[13px] shadow-md font-extrabold tracking-wide uppercase bg-white transform transition will-change-transform hover:scale-105"
          >
            + {{ reseau.structures_count - 5 }} antennes
          </nuxt-link>
        </div>
      </div>
    </section>

    <template v-if="missions.length">
      <section
        v-for="(antenne, index) in reseau.structures"
        :id="antenne.id"
        :key="antenne.id"
        class="py-16"
        :class="[{ 'bg-white': Math.abs(index % 2) == 1 }]"
      >
        <div class="container px-4 mx-auto">
          <div class="max-w-5xl mx-auto">
            <span class="font-bold uppercase text-[#696974] ml-[2px]">
              Antenne
            </span>
            <div class="flex items-baseline justify-stretch mb-12">
              <h3
                class="text-3xl sm:text-4xl !leading-normal text-gray-900 tracking-tighter font-extrabold relative mr-4 truncate"
              >
                {{ reseau.name }} de {{ antenne.city }}
              </h3>

              <nuxt-link
                :to="`/missions-benevolat?refinementList[structure.name][0]=${antenne.name}`"
                class="flex-none text-[#696974] text-lg hover:underline"
              >
                {{ missionsFrom(antenne.id).missionCount | formatNumber }}
                {{
                  missionsFrom(antenne.id).missionCount
                    | pluralize(['mission ›', 'missions ›'])
                }}
              </nuxt-link>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 xl:gap-8">
              <nuxt-link
                v-for="mission in missionsFrom(antenne.id).missions"
                :key="mission.id"
                class="card--mission--wrapper"
                :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
              >
                <CardMission :mission="mission" />
              </nuxt-link>
            </div>
          </div>
        </div>
      </section>
    </template>

    <template v-if="reseau.structures_count - 5 > 0">
      <hr class="mt-4" />

      <section class="container mx-auto px-4 py-16">
        <div class="max-w-5xl mx-auto">
          <h4 class="tracking-tight text-2xl mb-8">
            Les {{ reseau.structures_count - 5 }} autres antennes de
            <span class="font-extrabold">{{ reseau.name }}</span> en France
          </h4>

          <ul class="columns-layout list-disc pl-6">
            <li
              v-for="antenne in autresAntennes"
              :key="antenne.id"
              class="text-[#6A6A6A] text-sm"
            >
              <nuxt-link :to="antenne.full_url" class="hover:underline">
                {{ antenne.name }}
              </nuxt-link>
            </li>
          </ul>
        </div>
      </section>
    </template>

    <!-- FAIRE UN DON -->
    <div v-if="reseau.donation" id="faire-un-don" class="gradient mt-20">
      <div class="container px-4 mx-auto relative">
        <div
          class="max-w-[960px] mx-auto rounded-[24px] transform -translate-y-16 mb-6"
        >
          <div class="relative rounded-[24px] overflow-hidden shadow-lg">
            <img
              src="/images/bg_don.png"
              srcset="/images/bg_don@2x.png 2x"
              class="bg-img absolute object-cover w-full h-full"
            />

            <div
              class="absolute inset-0 w-full h-full opacity-90"
              :style="`background: ${color}`"
            ></div>

            <div class="relative text-white p-8 py-16 text-center">
              <h2
                class="font-bold text-center mb-6 text-3xl leading-8 tracking-tight sm:text-5xl sm:leading-tight"
              >
                <span>Faites un don à l'organisation</span>
                <br class="hidden xl:block" />
                <span class="font-extrabold">{{ reseau.name }}</span>
              </h2>

              <p class="text-xl max-w-xl mx-auto">
                Plus que jamais, l'organisation {{ reseau.name }} a besoin de
                votre générosité
              </p>
            </div>
          </div>

          <div>
            <div
              class="text-center transform -translate-y-1/2"
              :class="[
                {
                  'absolute inset-x-0':
                    !reseau.donation.includes('helloasso') &&
                    !reseau.donation.includes('leetchi') &&
                    !reseau.donation.includes('microdon') &&
                    !reseau.donation.includes('ulule'),
                },
              ]"
            >
              <button
                class="mx-auto flex items-center justify-center font-extrabold cursor-pointer shadow-lg text-xl leading-6 rounded-full text-white bg-jva-green py-4 px-10 hover:shadow-lg hover:scale-105 focus:scale-105 !outline-none transform transition will-change-transform"
                @click="goTo(reseau.donation)"
              >
                Faire un don
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 ml-2"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                  />
                </svg>
              </button>
            </div>

            <div
              v-if="
                reseau.donation.includes('helloasso') ||
                reseau.donation.includes('leetchi') ||
                reseau.donation.includes('microdon') ||
                reseau.donation.includes('ulule')
              "
              class="-mt-7 pt-6"
            >
              <div class="flex items-center justify-center">
                <span
                  class="uppercase text-gray-500 mr-2"
                  style="font-size: 10px"
                  >Par</span
                >

                <img
                  v-if="reseau.donation.includes('helloasso')"
                  src="/images/helloasso.svg"
                  alt="Helloasso"
                  class="flex-none"
                  width="92px"
                />

                <img
                  v-if="reseau.donation.includes('leetchi')"
                  src="/images/leetchi.png"
                  srcset="/images/leetchi@2x.png 2x"
                  alt="Leetchi"
                  class="flex-none"
                />

                <img
                  v-if="reseau.donation.includes('ulule')"
                  src="/images/ulule.svg"
                  alt="Ulule"
                  class="flex-none"
                  width="92px"
                />

                <img
                  v-if="reseau.donation.includes('microdon')"
                  src="/images/microdon.png"
                  srcset="/images/microdon@2x.png 2x"
                  alt="Microdon"
                  class="flex-none"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ROW 3 -->
    <div
      id="infos"
      class="relative bg-white md:grid md:grid-cols-3 lg:grid-cols-2"
    >
      <!-- 3 -- LEFT -->
      <div class="col-span-2 lg:col-span-1">
        <div class="px-4 max-w-3xl ml-auto">
          <div class="pt-4 pb-8 md:p-8 xl:p-16">
            <h2
              class="mb-6 text-3xl leading-8 font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
            >
              <span>Contactez l'organisation</span>
              <br class="hidden xl:block" />
              <span class="font-extrabold">{{ reseau.name }}</span>
            </h2>

            <div class="mb-8">
              <div
                class="text-gray-400 font-bold uppercase tracking-wider text-sm"
              >
                Adresse
              </div>
              <p>{{ reseau.full_address }}</p>
            </div>

            <div>
              <div
                class="text-gray-400 font-bold uppercase tracking-wider text-sm"
              >
                Contact
              </div>
              <p>
                <span v-if="reseau.phone">{{ reseau.phone }}<br /></span>
                <span v-if="reseau.email">{{ reseau.email }}</span>
                <span v-if="!reseau.email && !reseau.phone">
                  Non renseigné
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- 3 -- RIGHT -->
      <div>
        <iframe
          width="100%"
          height="100%"
          style="border: 0; min-height: 320px"
          loading="lazy"
          allowfullscreen
          :src="`https://www.google.com/maps/embed/v1/place?key=${$config.google.places}
            &q=${reseau.full_address}`"
        />
      </div>
    </div>
  </div>
</template>

<script>
// TODO REFACTORING AVEC ORGANISATIONS

export default {
  layout: 'organisation',
  async asyncData({ $api, params, error, $algoliaApi }) {
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
      missions: [],
      structures: [],
    }
  },
  async fetch() {
    const missions = []
    await Promise.all(
      this.reseau.structures.map(async (antenne) => {
        const config = {
          filters: `structure.id = ${antenne.id}`,
          hitsPerPage: 3,
        }
        const missionsData = await this.$algoliaApi.getMissions(config)
        missions.push({
          antenneId: antenne.id,
          missions: missionsData.json.hits,
          missionCount: missionsData.json.nbHits,
        })
      })
    )
    this.$set(this, 'missions', missions)

    const { data: structures } = await this.$api.getStructuresFromReseau(
      this.reseau.id
    )
    this.$set(this, 'structures', structures)
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
    autresAntennes() {
      return this.structures.filter(
        (antenne) =>
          !this.reseau.structures.find(
            (highlightedAntennas) => highlightedAntennas.id == antenne.id
          )
      )
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
    missionsFrom(antenneId) {
      return this.missions.find((antenne) => {
        return antenne.antenneId == antenneId
      })
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

.columns-layout {
  @screen sm {
    column-count: 2;
    column-gap: 2rem;
  }
  @screen lg {
    column-count: 3;
  }
  @apply space-y-6;
}

.gradient {
  background: linear-gradient(
    to bottom,
    #ffffff 43.75%,
    rgba(255, 255, 255, 0) 100%
  );
}
</style>
