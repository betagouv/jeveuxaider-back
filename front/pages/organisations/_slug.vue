<template>
  <div>
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
            <Breadcrumb
              class="breadcrumb"
              :items="[{ label: organisation.name }]"
            />

            <img
              v-if="organisation.logo"
              :src="
                organisation.logo.large
                  ? organisation.logo.large
                  : organisation.logo.original
              "
              :alt="organisation.name"
              class="my-8 h-auto"
              style="max-width: 16rem"
            />

            <h1
              class="mt-2 mb-6 text-3xl leading-8 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-tight"
            >
              <div v-if="legalStatus">Découvrez {{ legalStatus }}</div>
              <span class="font-extrabold">{{ organisation.name }}</span>
            </h1>

            <client-only :placeholder="organisation.description">
              <v-clamp
                tag="div"
                :max-lines="5"
                autoresize
                class="text-gray-500 text-lg"
                :expanded="expandDescription"
              >
                {{ organisation.description }}

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
          class="grid divide-x divide-gray-200 text-center border-t"
          :class="[
            { 'grid-cols-3': missions.data.length && organisation.donation },
            {
              'grid-cols-2':
                (missions.data.length && !organisation.donation) ||
                (organisation.donation && !missions.data.length),
            },
          ]"
        >
          <button
            v-if="missions.data.length"
            v-scroll-to="'#missions'"
            class="footer--button"
          >
            Devenir bénévole
          </button>

          <button
            v-if="organisation.donation"
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
          :src="`/images/organisations/domaines/${organisation.image_1}.jpg`"
          :srcset="`/images/organisations/domaines/${organisation.image_1}@2x.jpg 2x`"
          class="md:absolute object-cover w-full md:w-1/3 lg:w-1/2 h-full"
          @error="defaultImg($event, 'image_1')"
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
          :src="`/images/organisations/domaines/${organisation.image_2}.jpg`"
          :srcset="`/images/organisations/domaines/${organisation.image_2}@2x.jpg 2x`"
          class="md:absolute object-cover w-full md:w-1/3 lg:w-1/2 h-full"
          @error="defaultImg($event, 'image_2')"
        />
      </div>

      <!-- 2 -- RIGHT -->
      <div
        class="order-1 md:order-2 col-span-2 lg:col-span-1"
        :style="`background-color: ${color}`"
      >
        <div class="max-w-3xl mr-auto">
          <div class="text-white px-4 py-8 md:p-8 xl:p-16">
            <div
              v-if="organisation.places_left"
              class="font-extrabold text-4xl mb-4"
            >
              {{ organisation.places_left | formatNumber }}
              {{
                organisation.places_left
                  | pluralize(['bénévole recherché', 'bénévoles recherchés'])
              }}
            </div>

            <!-- DOMAINES -->
            <template v-if="organisation.domaines">
              <div class="flex items-center mb-4">
                <div class="flex-none uppercase font-bold text-sm mr-4">
                  Causes défendues
                </div>
                <hr class="w-full border-white opacity-25" />
              </div>

              <div v-if="!organisation.domaines_with_image.length">
                Non renseigné
              </div>

              <div v-else class="grid lg:grid-cols-2 gap-3 xl:gap-x-6">
                <div
                  v-for="domaine in organisation.domaines_with_image"
                  :key="domaine.id"
                  class="flex items-start"
                >
                  <div class="flex-none w-6 h-6 mr-3">
                    <img :src="domaine.image" :alt="domaine.name.fr" />
                  </div>
                  <div class="">{{ domaine.name.fr }}</div>
                </div>
              </div>
            </template>

            <!-- PUBLICS -->
            <template v-if="organisation.publics_beneficiaires">
              <div class="flex items-center mb-4 mt-8">
                <div class="flex-none uppercase font-bold text-sm mr-4">
                  Bénéficiaires
                </div>
                <hr class="w-full border-white opacity-25" />
              </div>

              <div v-if="!organisation.publics_beneficiaires.length">
                Non renseigné
              </div>

              <div
                v-for="(
                  public_beneficiaire, key
                ) in organisation.publics_beneficiaires"
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
                organisation.website ||
                organisation.facebook ||
                organisation.twitter ||
                organisation.instagram
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
                  v-if="organisation.website"
                  class="m-1 hover:scale-110 transform transition duration-150 ease-in-out will-change-transform focus:outline-none focus-within:shadow-outline border border-white rounded-full w-10 h-10 flex items-center justify-center"
                  @click="goTo(organisation.website)"
                >
                  <img
                    class="flex-none"
                    src="/images/link.svg"
                    :alt="organisation.name"
                  />
                </button>

                <!-- FACEBOOK -->
                <button
                  v-if="organisation.facebook"
                  class="m-1 hover:scale-110 transform transition duration-150 ease-in-out will-change-transform focus:outline-none focus-within:shadow-outline border border-white rounded-full w-10 h-10 flex items-center justify-center"
                  @click="goTo(organisation.facebook)"
                >
                  <img
                    class="flex-none"
                    src="/images/facebook.svg"
                    alt="Facebook"
                  />
                </button>

                <!-- TWITTER -->
                <button
                  v-if="organisation.twitter"
                  class="m-1 hover:scale-110 transform transition duration-150 ease-in-out will-change-transform focus:outline-none focus-within:shadow-outline border border-white rounded-full w-10 h-10 flex items-center justify-center"
                  @click="goTo(organisation.twitter)"
                >
                  <img
                    class="flex-none"
                    src="/images/twitter_white_2.svg"
                    alt="twitter"
                  />
                </button>

                <!-- INSTAGRAM -->
                <button
                  v-if="organisation.instagram"
                  class="m-1 hover:scale-110 transform transition duration-150 ease-in-out will-change-transform focus:outline-none focus-within:shadow-outline border border-white rounded-full w-10 h-10 flex items-center justify-center"
                  @click="goTo(organisation.instagram)"
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

    <!-- MISSIONS -->
    <div v-if="missions.data.length" id="missions" class="pt-16 pb-32">
      <div class="container px-4 mx-auto">
        <h2
          class="text-center mb-12 text-3xl leading-8 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-tight"
        >
          <span>Trouvez une mission dans {{ legalStatus }}</span>
          <br class="hidden xl:block" />
          <span class="font-extrabold">{{ organisation.name }}</span>
        </h2>

        <div class="flex flex-wrap justify-center">
          <nuxt-link
            v-for="mission in missions.data"
            :key="mission.id"
            class="card--mission--wrapper"
            :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
          >
            <CardMission :mission="mission" />
          </nuxt-link>
        </div>

        <div
          v-if="missions.current_page != missions.last_page"
          class="text-center mt-6"
        >
          <nuxt-link
            :to="`/missions-benevolat?refinementList[structure.name][0]=${organisation.name}`"
          >
            <button
              class="uppercase shadow-lg text-sm font-bold rounded-full text-gray-500 bg-white py-3 px-8 hover:scale-105 transform transition duration-150 ease-in-out"
            >
              Plus de missions
            </button>
          </nuxt-link>
        </div>
      </div>
    </div>

    <!-- FAIRE UN DON -->
    <div
      v-if="organisation.donation"
      id="faire-un-don"
      class="gradient"
      :class="[{ 'py-16': !missions.data.length }]"
    >
      <div class="container px-4 mx-auto">
        <div
          class="card--don mx-auto shadow-lg"
          :class="[{ 'transform -translate-y-16': missions.data.length }]"
        >
          <div class="relative">
            <img
              src="/images/bg_don.png"
              srcset="/images/bg_don@2x.png 2x"
              class="bg-img absolute object-cover w-full h-full"
            />

            <div
              class="absolute inset-0 w-full h-full opacity-70"
              :style="`background: ${color}`"
            ></div>

            <div class="relative text-white p-8 py-16 text-center">
              <h2
                class="font-extrabold text-center mb-6 text-3xl leading-8 tracking-tight sm:text-5xl sm:leading-tight"
              >
                <span>Faites un don à {{ legalStatus }}</span>
                <br class="hidden xl:block" />
                <span>{{ organisation.name }}</span>
              </h2>

              <p class="text-xl">
                Plus que jamais, {{ legalStatus }} a besoin de votre générosité
              </p>
            </div>
          </div>

          <div class="bg-white">
            <div class="text-center transform -translate-y-1/2">
              <button
                class="font-extrabold cursor-pointer shadow-lg text-xl leading-6 rounded-full text-white bg-green-400 py-4 px-10 hover:shadow-lg hover:scale-105 focus:scale-105 focus:outline-none transform transition duration-150 ease-in-out will-change-transform"
                @click="goTo(organisation.donation)"
              >
                Faire un don
              </button>
            </div>

            <div
              v-if="
                organisation.donation.includes('helloasso') ||
                organisation.donation.includes('leetchi') ||
                organisation.donation.includes('microdon') ||
                organisation.donation.includes('ulule')
              "
              class="-mt-7 p-6"
            >
              <div class="flex items-center justify-center">
                <span
                  class="uppercase text-gray-500 mr-2"
                  style="font-size: 10px"
                  >Par</span
                >

                <img
                  v-if="organisation.donation.includes('helloasso')"
                  src="/images/helloasso.svg"
                  alt="Helloasso"
                  class="flex-none"
                  width="92px"
                />

                <img
                  v-if="organisation.donation.includes('leetchi')"
                  src="/images/leetchi.png"
                  srcset="/images/leetchi@2x.png 2x"
                  alt="Leetchi"
                  class="flex-none"
                />

                <img
                  v-if="organisation.donation.includes('ulule')"
                  src="/images/ulule.svg"
                  alt="Ulule"
                  class="flex-none"
                  width="92px"
                />

                <img
                  v-if="organisation.donation.includes('microdon')"
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
      <div class="col-span-2 lg:col-span-1 md:border-b">
        <div class="px-4 max-w-3xl ml-auto">
          <div class="pt-4 pb-8 md:p-8 lg:pt-6 xl:p-16 xl:pt-8">
            <h2
              class="mt-2 mb-6 text-3xl leading-8 font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
            >
              <span v-if="legalStatus">Contactez {{ legalStatus }}</span>
              <br class="hidden xl:block" />
              <span class="font-extrabold">{{ organisation.name }}</span>
            </h2>

            <div class="mb-8">
              <div class="text-gray-500 font-bold uppercase">Adresse</div>
              <p>{{ organisation.full_address }}</p>
            </div>

            <div>
              <div class="text-gray-500 font-bold uppercase">Contact</div>
              <p>
                <span v-if="organisation.phone">
                  {{ organisation.phone }}<br />
                </span>
                <span v-if="organisation.email">{{ organisation.email }}</span>
                <span v-if="!organisation.email && !organisation.phone"
                  >Non renseigné</span
                >
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
            &q=${organisation.full_address}`"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'organisation',
  async asyncData({ $api, params, error }) {
    const organisation = await $api.getStructureBySlug(params.slug)

    if (!organisation) {
      return error({ statusCode: 404 })
    }

    const missions = await $api.fetchStructureAvailableMissionsWithPagination(
      organisation.id,
      {
        append: 'domaines',
        itemsPerPage: 6,
        sort: '-places_left',
      }
    )

    return {
      organisation,
      missions: missions.data,
    }
  },
  data() {
    return {
      expandDescription: false,
    }
  },
  head() {
    return {
      title: `${this.organisation.statut_juridique} ${this.organisation.name} - Devenez bénévole dans ${this.legalStatus} ${this.organisation.name} - JeVeuxAider.gouv.fr`,
      link: [
        {
          rel: 'canonical',
          href: `https://www.jeveuxaider.gouv.fr/organisation/${this.organisation.id}`,
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.organisation.description
            ? this.organisation.description
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
    legalStatus() {
      let output
      switch (this.organisation.statut_juridique) {
        case 'Association':
          output = "l'association"
          break
        case 'Collectivité':
          output = 'la collectivité'
          break
        case 'Structure publique':
          output = "l'organisation publique"
          break
        case 'Structure privée':
          output = "l'organisation privée"
          break
      }

      return output
    },
    color() {
      return this.organisation.color ? this.organisation.color : '#d33c4a'
    },
  },
  methods: {
    goTo(url) {
      window.open(url, '_blank')
    },
    defaultImg(e, field) {
      switch (field) {
        case 'image_1':
          e.target.src = `/images/bg_orga_placeholder.jpg`
          e.target.srcset = `/images/bg_orga_placeholder@2x.jpg 2x`
          break
        case 'image_2':
          e.target.src = `/images/bg_orga_placeholder2.jpg`
          e.target.srcset = `/images/bg_orga_placeholder2@2x.jpg 2x`
          break
      }
    },
    iconPublicType(publicType) {
      let icon
      switch (publicType) {
        case 'Personnes âgées':
          icon = require('@/assets/images/icones/personnes_agees.svg?include')
          break
        case 'Personnes en situation de handicap':
          icon = require('@/assets/images/icones/handicap.svg?include')
          break
        case 'Personnes à la rue':
          icon = require('@/assets/images/icones/helping_hand.svg?include')
          break
        case 'Parents':
          icon = require('@/assets/images/icones/parents.svg?include')
          break
        case 'jeunes_enfants':
          icon = require('@/assets/images/icones/jeunes_enfants.svg?include')
          break
        case 'Tous publics':
          icon = require('@/assets/images/icones/tous_public.svg?include')
          break
      }

      return icon
    },
  },
}
</script>

<style lang="sass" scoped>
*
  @apply border-gray-200

.breadcrumb
  border-bottom: 0 !important
  ::v-deep ol
    @apply px-0 #{!important}

.public-wrapper
  ::v-deep svg
    @apply w-full h-full

.card--mission--wrapper
  width: 100%
  @apply border-0 shadow-none p-0 mb-6
  @screen sm
    width: 330px
    @apply m-3 flex flex-col

.gradient
  background: linear-gradient(to bottom, #FFFFFF 43.75%, rgba(255, 255, 255, 0) 100%)

.card--don
  border-radius: 24px
  max-width: 960px
  overflow: hidden
  @apply bg-white

.footer--button
  font-size: 10px
  @apply font-bold uppercase py-6 outline-none transition-colors ease-in-out duration-200
  &:focus-visible,
  &:hover
    @apply bg-gray-100
  @screen sm
    @apply text-sm
</style>
