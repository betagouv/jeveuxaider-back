<template>
  <div>
    <SectionOrganisationEditShortcut
      :link="`/dashboard/structure/${organisation.id}/edit`"
    />

    <SectionOrganisationPresentation
      :organisation="organisation"
      :src-set="image1"
    >
      <footer
        slot="anchors"
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
    </SectionOrganisationPresentation>

    <SectionOrganisationDetails :organisation="organisation" :src-set="image2">
      <div
        v-if="organisation.places_left > 0"
        slot="nbBenevoles"
        class="text-2xl sm:text-4xl font-extrabold text-white mb-8 tracking-tight"
      >
        {{ organisation.places_left | formatNumber }}
        {{
          organisation.places_left
            | pluralize(['bénévole recherché', 'bénévoles recherchés'])
        }}
      </div>
    </SectionOrganisationDetails>

    <!-- MISSIONS -->
    <div v-if="missions.data.length" id="missions" class="pt-16 pb-32">
      <div class="container px-4 mx-auto">
        <h2
          class="text-center mb-12 text-3xl sm:text-5xl sm:!leading-[1.1] tracking-tighter text-gray-900"
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

        <div class="text-center mt-6">
          <nuxt-link
            :to="`/missions-benevolat?refinementList[structure.name][0]=${organisation.name}`"
          >
            <button
              class="uppercase shadow-lg text-sm font-bold rounded-full text-gray-500 bg-white py-3 px-8 hover:scale-105 transform transition"
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
      <div class="container px-4 mx-auto relative">
        <div
          class="card--don mx-auto rounded-[24px]"
          :class="[{ 'transform -translate-y-16 mb-6': missions.data.length }]"
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

          <div>
            <div
              class="text-center transform -translate-y-1/2"
              :class="[
                {
                  'absolute inset-x-0':
                    !organisation.donation.includes('helloasso') &&
                    !organisation.donation.includes('leetchi') &&
                    !organisation.donation.includes('microdon') &&
                    !organisation.donation.includes('ulule'),
                },
              ]"
            >
              <button
                class="mx-auto flex items-center justify-center font-extrabold cursor-pointer shadow-lg text-xl leading-6 rounded-full text-white bg-jva-green py-4 px-10 hover:shadow-lg hover:scale-105 focus:scale-105 !outline-none transform transition will-change-transform"
                @click="goTo(organisation.donation)"
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
                organisation.donation.includes('helloasso') ||
                organisation.donation.includes('leetchi') ||
                organisation.donation.includes('microdon') ||
                organisation.donation.includes('ulule')
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

    <SectionOrganisationContact :organisation="organisation" />
  </div>
</template>

<script>
import OrganisationMixin from '@/mixins/OrganisationMixin'

export default {
  mixins: [OrganisationMixin],
  layout: 'organisation',
  async asyncData({ $api, params, error, redirect }) {
    const organisation = await $api.getAssociationBySlugOrId(params.slug)
    if (/^\d+$/.test(params.slug)) {
      // Redirect orga/id vers orga/slug
      redirect(301, `/organisations/${organisation.slug}`)
    }

    if (!organisation) {
      return error({ statusCode: 404 })
    }

    // @todo: Utiliser plutôt $algoliaApi.getMissions
    const {
      data: missions,
    } = await $api.fetchStructureAvailableMissionsWithPagination(
      organisation.id,
      {
        append: 'domaines',
        pagination: 6,
        sort: '-places_left',
      }
    )

    return {
      organisation,
      missions,
    }
  },
  head() {
    const status =
      this.organisation.statut_juridique != 'Autre'
        ? this.organisation.statut_juridique
        : ''

    return {
      title: `${status} ${this.organisation.name} - Devenez bénévole dans ${this.legalStatus} ${this.organisation.name} - JeVeuxAider.gouv.fr`,
      link: [
        {
          rel: 'canonical',
          href: `https://www.jeveuxaider.gouv.fr/organisations/${this.organisation.slug}`,
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
    image1() {
      return (
        this.organisation?.override_image_1?.xxl ??
        (this.organisation?.image_1
          ? `/images/organisations/domaines/${this.organisation.image_1}.jpg, /images/organisations/domaines/${this.organisation.image_1}@2x.jpg`
          : this.organisation.domaines.length > 0
          ? `/images/organisations/domaines/${this.organisation.domaines[0].id}_1.jpg, /images/organisations/domaines/${this.organisation.domaines[0].id}_1@2x.jpg`
          : `/images/organisations/domaines/1_1.jpg, /images/organisations/domaines/1_1@2x.jpg`)
      )
    },
    image2() {
      return (
        this.organisation?.override_image_2?.xxl ??
        (this.organisation?.image_2
          ? `/images/organisations/domaines/${this.organisation.image_2}.jpg, /images/organisations/domaines/${this.organisation.image_2}@2x.jpg`
          : this.organisation.domaines.length > 0
          ? `/images/organisations/domaines/${this.organisation.domaines[0].id}_1.jpg, /images/organisations/domaines/${this.organisation.domaines[0].id}_1@2x.jpg`
          : `/images/organisations/domaines/2_1.jpg, /images/organisations/domaines/2_1@2x.jpg`)
      )
    },
  },
  methods: {
    goTo(url) {
      window.plausible &&
        window.plausible('Click Module de don - Page Orga', {
          props: { isLogged: this.$store.getters.isLogged },
        })
      window.open(url, '_blank')
    },
  },
}
</script>

<style lang="postcss" scoped>
* {
  @apply border-gray-200;
}

.card--mission--wrapper {
  width: 100%;
  @apply border-0 shadow-none p-0 mb-6;
  @screen sm {
    width: 330px;
    @apply m-3 flex flex-col;
  }
}

.gradient {
  background: linear-gradient(
    to bottom,
    #ffffff 43.75%,
    rgba(255, 255, 255, 0) 100%
  );
}

.card--don {
  max-width: 1038px;
}
</style>
