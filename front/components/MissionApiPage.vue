<template>
  <div>
    <div class="absolute w-full" style="height: 360px">
      <img
        src="/images/bg_header_mission.jpg"
        alt="Mission bénévolat"
        class="object-cover w-full h-full"
      />
    </div>

    <div class="relative mb-12">
      <Breadcrumb
        theme="transparent"
        :items="[
          { label: 'Missions de bénévolat', link: '/missions-benevolat' },
          {
            label: mission.domaine_name,
            link: `/missions-benevolat?refinementList[domaines][0]=${mission.domaine_name}`,
          },
          {
            label: breadcrumbTitle,
            h1: true,
          },
        ]"
        style="filter: drop-shadow(rgba(0, 0, 0, 0.5) 1px 1px 1px)"
      />

      <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg">
          <div class="lg:flex">
            <div class="flex-grow px-6 py-8 lg:flex-shrink-1 lg:p-12">
              <div class="mb-4">
                <div class="-m-2 flex flex-wrap">
                  <span
                    v-if="mission.domaine_name"
                    class="m-2 inline-flex px-3 py-1 rounded-full text-sm leading-5 font-semibold tracking-wide uppercase bg-indigo-100 text-[#1f0391]"
                    >{{ mission.domaine_name }}</span
                  >
                  <template v-if="tags">
                    <span
                      v-for="tag in tags"
                      :key="tag"
                      class="m-2 inline-flex px-3 py-1 rounded-full text-sm leading-5 font-semibold tracking-wide uppercase bg-gray-100 text-gray-900"
                    >
                      {{ tag }}
                    </span>
                  </template>
                </div>
              </div>

              <h2
                class="mt-4 pb-3 text-2xl sm:text-4xl leading-7 sm:leading-10 font-bold text-gray-900"
              >
                {{ mission.name }}
              </h2>

              <div class="mb-8">
                <ul class="mt-5 lg:grid lg:grid-cols-12 lg:gap-x-6 lg:gap-y-5">
                  <li class="flex items-start lg:col-span-6">
                    <div class="flex-shrink-0">
                      <template v-if="mission.type == 'Mission en présentiel'">
                        <img
                          src="/images/picker.svg"
                          width="29"
                          class="mt-2"
                          alt="picker"
                        />
                      </template>
                      <template v-else>
                        <img
                          src="/images/maison.svg"
                          width="29"
                          class="mt-2"
                          alt="maison"
                        />
                      </template>
                    </div>
                    <p class="ml-4 text-md font-bold leading-5 text-gray-900">
                      <span class="uppercase text-sm text-gray-500 font-medium">
                        <template
                          v-if="mission.type == 'Mission en présentiel'"
                        >
                          Mission en présentiel
                        </template>
                        <template v-else> Mission à distance </template>
                      </span>

                      <br />

                      <template v-if="mission.type == 'Mission en présentiel'">
                        {{ mission.zip }} {{ mission.city }}
                      </template>
                      <template v-else
                        >Réalisez cette mission depuis chez vous</template
                      >
                    </p>
                  </li>
                </ul>
              </div>

              <hr class="border-gray-200 mb-8" />

              <div>
                <h2 class="text-lg font-medium">
                  <span>L'organisation</span>
                  <b class="text-[#070191]">
                    {{ structure.name }}
                  </b>
                </h2>
              </div>

              <!-- <div
                v-if="structure.description"
                class="mt-2 text-base leading-7 text-gray-600"
              >
                <client-only>
                  <ReadMore
                    more-str="Lire plus"
                    :text="structure.description"
                    :max-chars="250"
                  />
                  <template slot="placeholder">
                    <div v-html="structure.description" />
                  </template>
                </client-only>
              </div> -->

              <div class="mt-4">
                <span>Mission proposée par</span>
                <span class="font-bold text-black">
                  {{ mission.publisher_name }}
                </span>
                <div class="text-gray-600 text-base">
                  Vous serez redirigé vers {{ mission.publisher_url }}
                </div>
              </div>
            </div>

            <div
              class="aside text-center bg-[#070191] rounded-b-lg lg:rounded-b-none lg:rounded-r-lg lg:flex-shrink-0 lg:flex lg:flex-col lg:justify-center"
            >
              <div class="py-8 px-6 lg:p-12">
                <div
                  v-if="formattedDate"
                  class="text-base leading-6 text-indigo-200 mb-2"
                  v-html="formattedDate"
                />

                <!-- <div
                  class="inline-flex px-5 py-1 rounded-full text-sm leading-5 font-semibold tracking-wide uppercase bg-indigo-100 text-[#070191]"
                >
                  <template v-if="mission.has_places_left === true">
                    {{ mission.places_left | formatNumber }}
                    {{
                      mission.places_left
                        | pluralize(['place disponible', 'places disponibles'])
                    }}
                  </template>
                  <template v-else-if="mission.has_places_left === false">
                    Complet
                  </template>
                  <template v-else>Nombre de places non défini</template>
                </div> -->

                <div
                  class="inline-flex px-5 py-1 rounded-full text-sm leading-5 font-semibold tracking-wide uppercase bg-indigo-100 text-[#070191]"
                >
                  {{ placesLeftText }}
                </div>

                <!-- <div class="text-indigo-300 text-sm mt-1">
                  {{ placesLeftText }}
                </div> -->

                <div
                  class="mt-4 flex items-center justify-center text-5xl leading-none font-extrabold text-gray-900"
                />

                <div class="mt-6">
                  <a
                    :href="mission.application_url"
                    target="_blank"
                    class="max-w-sm mx-auto w-full flex items-center justify-center border border-transparent rounded-full text-white bg-[#16a972] hover:bg-[#0e9f6e] focus:outline-none focus:shadow-outline transition duration-150 ease-in-out font-bold text-md sm:text-xl px-5 py-3 pb-4"
                    >Je propose mon aide
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
                      /></svg
                  ></a>
                </div>

                <div
                  class="mt-8 lg:mt-12 block text-center text-sm leading-2 font-medium text-indigo-300 max-w-xs mx-auto"
                >
                  À plusieurs on est meilleur ! Et si vous partagiez cette
                  mission à votre entourage ?
                </div>

                <div class="mt-5">
                  <div class="-m-3 flex justify-center">
                    <!-- Mail -->
                    <a
                      :href="`mailto:?&subject=${mission.name}&body=J'ai trouvé ma future mission de bénévolat sur JeVeuxAider.%0d%0aRejoignez le mouvement #ChacunPourTous%0d%0a${baseUrl}${$router.currentRoute.fullPath}`"
                      class="m-3 text-indigo-300 hover:text-white transition"
                    >
                      <svg
                        x="0px"
                        y="0px"
                        viewBox="0 0 41 38"
                        style="enable-background: new 0 0 41 38"
                        xml:space="preserve"
                        class="h-6 w-7"
                      >
                        <path
                          fill="currentColor"
                          d="M37.6,7.9v22.2H3.4V7.9H37.6z M41,4.8H0v28.5h41V4.8z M37.6,30.1L26.4,19.6l-5.9,5.5l-5.8-5.6L3.4,30.1L12,17.4 L3.4,7.9l17.1,11.8l17-11.8L29,17.4L37.6,30.1z"
                        />
                      </svg>
                    </a>

                    <!-- Facebook -->
                    <a
                      target="_blank"
                      :href="`https://www.facebook.com/sharer/sharer.php?u=${baseUrl}${$router.currentRoute.fullPath}`"
                      class="m-3 text-indigo-300 hover:text-white transition"
                    >
                      <svg class="h-6 w-6" viewBox="0 0 155.139 155.139">
                        <path
                          d="m89.584 155.139v-70.761h23.742l3.562-27.585h-27.304v-17.609c0-7.984 2.208-13.425 13.67-13.425l14.595-.006v-24.673c-2.524-.328-11.188-1.08-21.272-1.08-21.057 0-35.473 12.853-35.473 36.452v20.341h-23.814v27.585h23.814v70.761z"
                          fill="currentColor"
                        />
                      </svg>
                    </a>

                    <!-- Twitter -->
                    <a
                      target="_blank"
                      :href="`https://twitter.com/intent/tweet?url=${baseUrl}${$router.currentRoute.fullPath}&text=J'ai trouvé ma future mission de bénévolat sur JeVeuxAider. Rejoignez le mouvement #ChacunPourTous`"
                      class="m-3 text-indigo-300 hover:text-white transition"
                    >
                      <svg
                        class="w-6 h-6"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path
                          d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"
                        ></path>
                      </svg>
                    </a>

                    <!-- Linkedin -->
                    <a
                      target="_blank"
                      :href="`https://www.linkedin.com/shareArticle?mini=true&url=${baseUrl}${$router.currentRoute.fullPath}`"
                      class="m-3 text-indigo-300 hover:text-white transition"
                    >
                      <svg
                        class="w-6 h-6"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z"
                          clip-rule="evenodd"
                        ></path>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-8 sm:mt-12 container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg">
          <div class="flex justify-between">
            <div class="px-6 pt-8 lg:p-12 lg:pb-0 lg:mr-16">
              <h3
                class="text-2xl sm:text-4xl leading-7 sm:leading-10 font-bold text-gray-900"
              >
                Tout savoir sur cette mission
              </h3>

              <div
                class="inline-flex w-16 mt-8 mb-2"
                style="height: 2px; background-color: #d33c4a"
              ></div>

              <div
                class="whitespace-pre-line text-gray-600"
                style="word-break: break-word"
              >
                {{ mission.description }}
              </div>
            </div>
            <img class="my-8 hidden xl:block" src="/images/france_right.svg" />
          </div>

          <div class="py-12 px-6">
            <a
              :href="mission.application_url"
              target="_blank"
              class="mx-auto w-full sm:w-80 flex items-center justify-center border border-transparent rounded-full text-white bg-[#16a972] hover:bg-[#0e9f6e] focus:outline-none focus:shadow-outline transition duration-150 ease-in-out font-bold text-md sm:text-xl px-5 py-3 pb-4"
              >Je propose mon aide
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
                /></svg
            ></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Mission',
  props: {
    mission: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      baseUrl: this.$config.appUrl,
    }
  },
  computed: {
    structure() {
      return this.mission.structure
    },
    placesLeftText() {
      if (
        this.mission.publisher_name &&
        this.mission.publisher_name != 'Réserve Civique' &&
        this.mission.places_left > 99
      ) {
        return 'Plusieurs bénévoles recherchés'
      } else if (this.mission.has_places_left && this.mission.places_left > 0) {
        return (
          this.mission.places_left +
          ' ' +
          this.$options.filters.pluralize(this.mission.places_left, [
            'bénévole recherché',
            'bénévoles recherchés',
          ])
        )
      } else {
        return this.mission.has_places_left === false
          ? 'Complet'
          : 'Plusieurs bénévoles recherchés'
      }
    },
    formattedDate() {
      const startDate = this.mission.start_date
      const endDate = this.mission.end_date

      if (!endDate) {
        return null
      }

      if (this.$dayjs(endDate).diff(this.$dayjs(startDate), 'year') > 1) {
        return null
      }

      if (
        this.$dayjs(startDate).format('D MMMM YYYY') ==
        this.$dayjs(endDate).format('D MMMM YYYY')
      ) {
        return `Le <b class="text-white">${this.$dayjs(startDate).format(
          'D MMMM YYYY'
        )}</b>`
      }

      if (
        this.$dayjs(startDate).format('YYYY') !=
        this.$dayjs(endDate).format('YYYY')
      ) {
        return `Du <b class="text-white">${this.$dayjs(startDate).format(
          'D MMMM YYYY'
        )}</b> au <b class="text-white">${this.$dayjs(endDate).format(
          'D MMMM YYYY'
        )}</b>`
      } else {
        return `Du <b class="text-white">${this.$dayjs(startDate).format(
          'D MMMM'
        )}</b> au <b class="text-white">${this.$dayjs(endDate).format(
          'D MMMM YYYY'
        )}</b>`
      }
    },
    breadcrumbTitle() {
      return this.mission.city
        ? `Bénévolat ${this.mission.structure.name} à ${this.mission.city}`
        : `Bénévolat ${this.mission.structure.name}`
    },
    tags() {
      return this.mission.domaines.filter((domaine) => {
        return domaine != this.mission.domaine_name
      })
    },
  },
}
</script>

<style lang="postcss" scoped>
.aside {
  @screen lg {
    max-width: 410px;
    @apply flex-none w-full;
  }
}

::v-deep .el-dialog__title {
  @apply text-[#242526] text-xl font-bold;
}
</style>
