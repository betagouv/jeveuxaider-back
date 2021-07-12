<template>
  <div>
    <div class="relative">
      <div class="absolute w-full" style="height: 360px">
        <img
          src="/images/bg_header_mission.jpg"
          alt="Mission bénévolat"
          class="object-cover w-full h-full"
        />
      </div>
    </div>

    <div class="relative mb-12">
      <Breadcrumb
        theme="transparent"
        :items="[
          { label: 'Missions de bénévolat', link: '/missions-benevolat' },
          {
            label: domainName(mission),
            link: `/missions-benevolat?refinementList[domaines][0]=${domainName(
              mission
            )}`,
          },
          {
            label:
              mission.type == 'Mission en présentiel'
                ? `Bénévolat ${mission.structure.name} à ${mission.city}`
                : `Bénévolat ${mission.structure.name} à distance`,
            h1: true,
          },
        ]"
        style="filter: drop-shadow(rgba(0, 0, 0, 0.5) 1px 1px 1px)"
      />

      <div class="container mx-auto px-4">
        <div class="flex flex-wrap lg:flex-no-wrap gap-6">
          <div class="w-full">
            <div
              class="bg-white rounded-10 shadow-lg px-6 py-8 xl:py-12 xl:px-16"
            >
              <div
                class="
                  flex flex-wrap
                  lg:flex-no-wrap
                  gap-6
                  justify-between
                  items-center
                  lg:items-start
                "
              >
                <div
                  class="
                    flex-none
                    rounded-full
                    h-8
                    w-8
                    flex
                    items-center
                    justify-center
                    p-2
                    border-2
                    transform
                    will-change-transform
                    hover:scale-110
                    focus:scale-110
                    focus:outline-none
                    transition
                    ease-in-out
                    duration-150
                    cursor-pointer
                    sm:order-2
                  "
                  @click="onClickShare"
                >
                  <img src="/images/share.svg" alt="Partager" class="-ml-px" />
                </div>

                <div class="sm:order-1 flex flex-wrap gap-2">
                  <div
                    v-for="(tag, index) in [
                      domainName(mission),
                      ...mission.tags,
                    ]"
                    :key="index"
                    class="
                      inline-flex
                      px-3
                      py-1
                      rounded-full
                      text-xs
                      leading-5
                      font-semibold
                      tracking-wide
                      uppercase
                      truncate
                    "
                    :class="[
                      { 'bg-indigo-100 text-blue-900': index === 0 },
                      { 'bg-gray-100 text-gray-900': index !== 0 },
                    ]"
                  >
                    <template v-if="tag.name">
                      {{ tag.name.fr }}
                    </template>
                    <template v-else>
                      {{ tag }}
                    </template>
                  </div>
                </div>
              </div>

              <h1
                class="
                  mt-6
                  pb-3
                  text-2xl
                  sm:text-4xl
                  leading-7
                  sm:leading-10
                  font-extrabold
                  text-black
                  tracking-px
                "
              >
                {{ mission.name }}
              </h1>

              <div class="mt-2 mb-5 text-sm text-gray-777E90 font-medium">
                <span>Publié par </span>
                <img
                  v-if="mission.responsable.image"
                  :src="
                    mission.responsable.image.thumb
                      ? mission.responsable.image.thumb
                      : mission.responsable.image.original
                  "
                  :alt="`Portrait de ${mission.responsable.full_name}`"
                  class="
                    inline-flex
                    w-7
                    h-7
                    rounded-full
                    border-2 border-gray-200
                  "
                />
                <span class="text-gray-1000">
                  {{ mission.responsable.full_name }}
                </span>
                <span>de {{ structureType }}</span>
                <component
                  :is="
                    structure.statut_juridique == 'Association' &&
                    structure.state == 'Validée'
                      ? 'nuxt-link'
                      : 'span'
                  "
                  target="_blank"
                  :to="`/organisations/${structure.slug}`"
                  class="font-bold uppercase text-blue-800"
                >
                  <h2 class="inline">{{ structure.name }}</h2>
                </component>
              </div>

              <div class="flex items-center gap-4 mb-4">
                <div
                  class="flex-none font-bold text-xs uppercase text-gray-696974"
                >
                  PUBLICS AIDÉS
                </div>
                <hr class="text-gray-E6E8EC w-full" />
              </div>

              <div class="flex flex-wrap gap-2">
                <div
                  v-for="(
                    publicBeneficiaire, key
                  ) in mission.publics_beneficiaires"
                  :key="key"
                  class="
                    inline-flex
                    px-3
                    py-1
                    rounded-full
                    text-xs
                    leading-5
                    font-semibold
                    tracking-wide
                    uppercase
                    bg-gray-4E4E54
                    text-white
                  "
                >
                  {{
                    publicBeneficiaire
                      | labelFromValue('mission_publics_beneficiaires')
                  }}
                </div>
              </div>

              <div class="flex items-center gap-4 mt-8 mb-4">
                <div
                  class="flex-none font-bold text-xs uppercase text-gray-696974"
                >
                  COMPÉTENCES RECHERCHÉES
                </div>
                <hr class="text-gray-E6E8EC w-full" />
              </div>

              <div class="text-sm text-gray-777E90">
                {{ ['@todo', '@todo', '@todo'].join(' • ') }}
              </div>
            </div>

            <div class="mt-6 rounded-10 shadow-lg overflow-hidden">
              <template v-if="mission.type == 'Mission en présentiel'">
                <iframe
                  width="100%"
                  height="100%"
                  style="border: 0; min-height: 190px"
                  loading="lazy"
                  allowfullscreen
                  :src="`https://www.google.com/maps/embed/v1/place?key=${$config.google.places}
                    &q=${mission.full_address}`"
                />

                <div
                  class="
                    bg-white
                    px-6
                    xl:px-16
                    py-3
                    md:flex
                    flex-wrap
                    justify-between
                    text-sm
                  "
                >
                  <div class="uppercase font-bold" style="color: #393939">
                    <template v-if="mission.type == 'Mission en présentiel'">
                      Mission sur le terrain
                    </template>
                    <template v-else> Mission à distance </template>
                  </div>
                  <div class="text-gray-777E90">
                    📍 {{ mission.full_address }}
                  </div>
                </div>
              </template>

              <template v-else>
                <div class="relative">
                  <img
                    src="/images/mission_a_distance.jpg"
                    srcset="/images/mission_a_distance@2x.jpg 2x"
                    alt="Personne assise devant un ordinateur portable"
                    class="absolute inset-0 w-full h-full object-cover"
                  />

                  <div class="absolute inset-0 custom-gradient-2"></div>

                  <div class="text-white relative px-6 xl:px-16 py-8">
                    <div class="font-extrabold text-lg">Mission à distance</div>
                    <div>
                      Réalisez cette mission de bénévolat<br />
                      <strong>depuis chez vous</strong> ou
                      <strong>en autonomie</strong>
                    </div>
                  </div>
                </div>
              </template>
            </div>

            <div
              class="
                mt-6
                bg-white
                rounded-10
                shadow-lg
                px-6
                py-8
                xl:py-12
                xl:px-16
              "
            >
              <div class="font-extrabold text-lg tracking-px mb-4">
                Présentation de la mission
              </div>

              <h2
                class="text-gray-777E90 leading-7"
                v-html="mission.objectif"
              />

              <div
                class="
                  mt-6
                  p-6
                  md:p-8
                  xl:p-12
                  rounded-10
                  custom-gradient
                  relative
                "
              >
                <img
                  class="absolute right-0 bottom-0 p-6"
                  src="/images/quote.svg"
                  alt="Guillemets"
                />

                <div class="relative z-10 citation text-lg">
                  <h3 v-html="mission.information" />
                </div>
              </div>

              <div class="font-extrabold text-lg tracking-px mb-4 mt-10">
                Précisions
              </div>

              <div
                class="text-gray-777E90 leading-7"
                v-html="mission.description"
              />

              <div class="flex items-center gap-4 mt-8 mb-4">
                <div
                  class="flex-none font-bold text-xs uppercase text-gray-696974"
                >
                  MISSION OUVERTE ÉGALEMENT AUX
                </div>
                <hr class="text-gray-E6E8EC w-full" />
              </div>

              <div class="grid xl:grid-cols-2 gap-3">
                <div
                  v-for="(
                    public_beneficiaire, key
                  ) in mission.publics_beneficiaires"
                  :key="key"
                  class="flex items-center"
                >
                  <div
                    class="
                      public-wrapper
                      w-6
                      h-6
                      mr-3
                      flex
                      items-center
                      justify-center
                    "
                    v-html="iconPublicType(public_beneficiaire)"
                  />

                  <div class="text-sm text-gray-777E90">
                    {{
                      public_beneficiaire
                        | labelFromValue('mission_publics_beneficiaires')
                    }}
                  </div>
                </div>
              </div>
            </div>

            <div
              class="
                mt-6
                bg-white
                rounded-10
                shadow-lg
                px-6
                py-8
                xl:py-12
                xl:px-16
              "
            >
              <div class="flex flex-col sm:flex-row gap-6">
                <img
                  v-if="structure.logo"
                  :src="structure.logo.original"
                  :alt="structure.name"
                  class="mx-auto lg:mx-0 my-auto h-20 object-contain"
                  style="max-width: 150px"
                />

                <div>
                  <h2 class="font-bold text-xl tracking-px mb-4">
                    Découvrez {{ structureType }}
                    <component
                      :is="
                        structure.statut_juridique == 'Association' &&
                        structure.state == 'Validée'
                          ? 'nuxt-link'
                          : 'span'
                      "
                      target="_blank"
                      :to="`/organisations/${structure.slug}`"
                      class="font-extrabold uppercase"
                    >
                      {{ structure.name }}
                    </component>
                  </h2>

                  <client-only :placeholder="structure.description">
                    <v-clamp
                      :max-lines="3"
                      autoresize
                      class="text-gray-777E90 leading-7"
                    >
                      {{ structure.description }}
                    </v-clamp>
                  </client-only>

                  <nuxt-link
                    v-if="
                      structure.statut_juridique == 'Association' &&
                      structure.state == 'Validée'
                    "
                    :to="`/organisations/${structure.slug}`"
                    class="
                      inline-block
                      border-2 border-gray-E6E8EC
                      rounded-full
                      text-black
                      hover:border-black
                      focus:outline-none
                      focus:border-black
                      transition
                      duration-150
                      ease-in-out
                      font-bold
                      text-sm
                      px-4
                      py-2
                      mt-6
                    "
                  >
                    En savoir plus
                  </nuxt-link>
                </div>
              </div>
            </div>
          </div>

          <div class="flex-none w-full lg:w-96">
            <div
              class="rounded-10 overflow-hidden shadow-lg sticky"
              style="top: 24px"
            >
              <img
                src="/images/mission-default.jpg"
                srcset="/images/mission-default@2x.jpg 2x"
                alt=""
                class="w-full object-cover object-top"
                style="max-height: 210px"
              />

              <div
                v-if="structure.logo"
                class="logo-wrapper bg-white shadow-lg rounded-10 p-4"
              >
                <img
                  :src="structure.logo.original"
                  :alt="structure.name"
                  class="my-auto h-10 object-contain"
                  style="max-width: 100px"
                />
              </div>

              <div class="bg-white py-12">
                <div class="px-4 text-center">
                  <div class="font-extrabold text-xl">
                    Ils recherchent
                    {{ mission.participations_max | formatNumber }}
                    {{
                      mission.participations_max
                        | pluralize(['bénévole', 'bénévoles'])
                    }}
                  </div>

                  <template v-if="participationsCount">
                    <div
                      class="mt-2 uppercase text-gray-777E90 text-xs font-bold"
                    >
                      {{ participationsCount }}
                      {{
                        participationsCount
                          | pluralize([
                            'personne déjà inscrite',
                            'personnes déjà inscrites',
                          ])
                      }}
                    </div>

                    <div class="mt-4 flex justify-center">
                      <img
                        v-for="(portrait, index) in portraits"
                        :key="index"
                        :src="portrait.src"
                        :srcset="`${portrait.x2} 2x`"
                        alt=""
                        :class="[{ '-ml-1': index !== 0 }]"
                        class="portrait"
                      />
                      <div
                        v-if="participationsCount - 3 > 0"
                        class="
                          portrait-count
                          bg-white
                          font-bold
                          inline-flex
                          items-center
                          justify-center
                          rounded-full
                          text-xs
                          -ml-1
                        "
                      >
                        +{{ participationsCount - 3 }}
                      </div>
                    </div>
                  </template>
                </div>

                <div class="px-8 sm:px-32 lg:px-8 mt-8">
                  <div
                    v-if="dates"
                    class="grid sm:divide-x border-b"
                    :class="[{ 'sm:grid-cols-2': dates.length == 2 }]"
                  >
                    <div
                      v-for="(date, i) in dates"
                      :key="i"
                      :class="[{ 'sm:pr-3': i == 0 }, { 'sm:pl-3': i == 1 }]"
                      class="pb-3 mx-auto sm:mx-0"
                    >
                      <div
                        class="
                          flex
                          items-center
                          sm:items-start
                          flex-col
                          sm:flex-row
                          gap-2
                        "
                        :class="[{ 'justify-center': dates.length == 1 }]"
                      >
                        <img
                          src="/images/calendar.svg"
                          alt="Icône calendrier"
                          :class="[{ 'hidden sm:block': i == 1 }]"
                        />

                        <div class="font-bold text-center sm:text-left">
                          <div class="text-gray-777E90" style="font-size: 11px">
                            {{ date.label }}
                          </div>
                          <div class="text-black">
                            {{ date.date }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mx-12">
                  <div class="text-center">
                    <div
                      class="mt-6 uppercase text-gray-777E90 text-xs font-bold"
                    >
                      Engagement minimum
                    </div>
                    <div class="font-bold">
                      <span>@todo</span>
                      <span class="font-normal">par</span>
                      <span>@todo</span>
                    </div>
                  </div>

                  <ButtonJeProposeMonAide class="mt-6" :mission="mission" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="otherMissions.total > 0"
      class="bg-blue-282562 border-t-8 border-red-FC7069"
    >
      <div class="container mx-auto px-4">
        <div class="pt-16 pb-24">
          <div class="text-white font-bold text-4xl text-center mb-8">
            Vous pourriez aussi aimer&nbsp;…
          </div>

          <MissionsSlideshow class="mb-6" :missions="otherMissions.data" />

          <div class="text-center">
            <nuxt-link
              :to="`/missions-benevolat?refinementList[structure.name][0]=${structure.name}`"
              class="
                inline-block
                border-2 border-gray-500
                rounded-full
                text-white
                hover:border-white
                focus:outline-none
                focus:shadow-outline
                transition
                duration-150
                ease-in-out
                font-bold
                text-sm
                px-4
                py-2
                mt-6
              "
            >
              Plus de missions
            </nuxt-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Mission',
  async asyncData({ $api, params, error, store }) {
    const mission = await $api.getMission(params.id)

    if (!mission || !mission.structure) {
      return error({ statusCode: 404 })
    }

    if (mission.state == 'En attente de validation') {
      return error({ statusCode: 403 })
    }

    // Si mission signalée ou organisation désinscrite / signalée
    if (
      ['Signalée', 'Brouillon'].includes(mission.state) ||
      ['Désinscrite', 'Signalée'].includes(mission.structure.state)
    ) {
      if (store.getters.isLogged) {
        // Si on participe à cette mission ou si on est responsable de la structure
        if (
          !store.getters.user.profile.participations.filter(
            (participation) => participation.mission_id == mission.id
          ).length &&
          !store.getters.user.profile.structures.filter(
            (structure) => structure.id == mission.structure_id
          ).length
        ) {
          return error({ statusCode: 403 })
        }
      } else {
        return error({ statusCode: 403 })
      }
    }

    const otherMissions = await $api.fetchStructureAvailableMissions(
      mission.structure.id,
      {
        exclude: params.id,
        append: 'domaines',
      }
    )
    return {
      mission,
      otherMissions,
    }
  },
  data() {
    return {
      loading: true,
      mission: {},
      otherMissions: {},
      baseUrl: this.$config.appUrl,
      form: {
        content: `Bonjour,\nJe souhaite participer à cette mission et apporter mon aide. \nJe me tiens disponible pour échanger et débuter la mission 🙂\n`,
      },
      rules: {
        content: [
          {
            required: true,
            message: 'Entrez un message.',
            trigger: 'blur',
          },
          {
            min: 10,
            message: 'Votre message est trop court.',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  head() {
    return {
      title: this.mission.name.substring(0, 80),
      link: [
        {
          rel: 'canonical',
          href: `https://www.jeveuxaider.gouv.fr/missions-benevolat/${this.mission.id}/${this.mission.slug}`,
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.mission.description
            .replace(/<\/?[^>]+>/gi, ' ')
            .substring(0, 300),
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
    structure() {
      return this.mission.structure
    },
    structureType() {
      let status = this.$options.filters
        .labelFromValue(
          this.structure.statut_juridique,
          'structure_legal_status'
        )
        .toLowerCase()
      if (status == 'autre') {
        status = 'organisation'
      }
      return status.match('^[aieouAIEOU].*') ? `l'${status}` : `la ${status}`
    },
    participationsCount() {
      return 999
      // @todo uncomment
      // return this.mission.participations_max - this.mission.places_left
    },
    portraits() {
      const portraits = []
      const randomNumbers = []
      const portraitsCount = 5 // The total number of portraits existing
      const portraitsToGetCount = Math.min(this.participationsCount, 3)

      while (randomNumbers.length < portraitsToGetCount) {
        const result = Math.floor(Math.random() * portraitsCount) + 1
        if (!randomNumbers.includes(result)) {
          randomNumbers.push(result)
        }
      }

      randomNumbers.forEach((i) => {
        portraits.push({
          src: `/images/portraits/${i}.png`,
          x2: `/images/portraits/${i}@2x.png`,
        })
      })

      return portraits
    },
    dates() {
      const dates = []
      const startDate = this.mission.start_date?.substring(0, 10)
      const endDate = this.mission.end_date?.substring(0, 10)
      const startDateYear = startDate?.substring(0, 4)
      const endDateYear = endDate?.substring(0, 4)
      const format =
        startDate && endDate && startDateYear != endDateYear
          ? 'D MMMM YYYY'
          : 'D MMMM'

      if (startDate) {
        dates.push({
          date: this.$dayjs(startDate).format(format),
          label: 'À PARTIR DU',
        })
      }

      if (endDate) {
        dates.push({
          date: this.$dayjs(endDate).format(format),
          label: "JUSQU'AU",
        })
      }

      return dates
    },
  },
  created() {
    if (this.mission.responsable && this.$store.getters.profile) {
      this.form.content = `Bonjour ${this.mission.responsable.first_name},\nJe souhaite participer à cette mission et apporter mon aide. \nJe me tiens disponible pour échanger et débuter la mission 🙂\n${this.$store.getters.profile.first_name}`
    }
  },
  methods: {
    domainName(mission) {
      return mission.domaine && mission.domaine.name && mission.domaine.name.fr
        ? mission.domaine.name.fr
        : mission.template &&
          mission.template.domaine &&
          mission.template.domaine.name &&
          mission.template.domaine.name.fr
        ? mission.template.domaine.name.fr
        : null
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
    onClickShare() {
      this.$store.commit('setMissionSelected', this.mission)
      this.$store.commit('toggleShareOverlay')
    },
  },
}
</script>

<style lang="sass" scoped>
.aside
  @screen lg
    max-width: 410px
    @apply flex-none w-full

.comment-wrapper
  min-height: 200px
  ::v-deep ul,::v-deep ol
    @apply flex flex-col items-center
  @screen lg
    @apply relative
  .comment-wrapper--icon
    @apply hidden
    @screen lg
      @apply block
    @screen xl
      left: 5%

::v-deep .el-dialog__title
  @apply text-gray-800 text-xl font-bold

.custom-gradient
  background: linear-gradient(to right, #070191 5px, #eeedf7 5px)

.custom-gradient-2
  background: linear-gradient(90deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0) 70.1%)

.citation
  *,
  ::v-deep *
    display: inline
  h3
    &::before
      content: "“\00A0"
    &::after
      content: "\00A0”"

.public-wrapper
  ::v-deep svg
    @apply w-full h-full
    > *[fill]
      fill: #808080

.logo-wrapper
  left: 50%
  transform: translateX(-50%) translateY(-70%)
  @apply absolute

.portrait-count
  width: 34px
  height: 34px
  color: #B6B6B6

.portrait,
.portrait-count
  filter: drop-shadow(rgba(0, 0, 0, 0.1) 0 3px 7px)
</style>
