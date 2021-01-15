<template>
  <div>
    <div v-if="collectivity" class="relative">
      <img
        v-if="collectivity.banner && collectivity.banner.large"
        :src="collectivity.banner.large"
        alt=""
        class="absolute object-cover object-center w-full h-full"
      />

      <div
        :class="[
          'bg-primary',
          'absolute',
          'inset-0',
          { 'opacity-75': collectivity.banner },
        ]"
      />

      <div class="relative pt-1 pb-12 lg:py-12">
        <div class="container mx-auto px-4">
          <div class="py-8 text-center sm:text-left">
            <h1
              class="text-4xl max-w-4xl leading-none font-bold text-white sm:text-5xl md:text-6xl"
            >
              <template v-if="collectivity.title">{{
                collectivity.title
              }}</template>
              <template v-else
                >Rejoignez la Réserve Civique dans votre département</template
              >
            </h1>

            <p
              class="mt-5 text-base text-gray-100 max-w-xl sm:text-lg md:text-xl"
            >
              <template v-if="collectivity.description">{{
                collectivity.description
              }}</template>
              <template v-else>
                <b>{{ collectivity.name }}</b> • Votre organisation a besoin de
                renfort localement ? Vous souhaitez vous engager bénévolement au
                plus près de chez vous ? Rejoignez la Réserve Civique dans votre
                département.
              </template>
            </p>

            <div
              class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start"
            >
              <div class="text-center">
                <p class="text-xs leading-6 font-medium text-white">
                  Je veux aider
                </p>
                <router-link
                  v-if="!$store.getters.isLogged"
                  to="/register/volontaire"
                  class="shadow-lg w-full flex items-center justify-center px-10 py-3 text-base leading-6 font-medium rounded-full bg-white text-blue-800 hover:bg-gray-100 hover:bg-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-15"
                >
                  Devenir réserviste
                </router-link>
                <a
                  v-else
                  href="#search"
                  class="shadow-lg w-full flex items-center justify-center px-10 py-3 text-base leading-6 font-medium rounded-full bg-white text-blue-800 hover:bg-gray-100 hover:bg-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-15"
                >
                  Trouver une mission
                </a>
              </div>
              <div class="mt-3 sm:mt-0 sm:ml-6 text-center">
                <p class="text-xs leading-6 font-medium text-white">
                  Mon organisation a besoin de renfort
                </p>
                <router-link
                  :to="
                    $store.getters.isLogged &&
                    $store.getters.contextRole == 'responsable'
                      ? {
                          name: 'DashboardMissionFormAdd',
                          params: {
                            structureId:
                              $store.getters.structure_as_responsable.id,
                          },
                        }
                      : '/login'
                  "
                  class="shadow-lg w-full flex items-center justify-center px-8 py-3 border border-transparent border text-base leading-6 font-medium rounded-full text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-9"
                >
                  Proposer une mission
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="py-20 bg-gray-50 overflow-hidden shadow-lg">
      <div class="relative container mx-auto px-6 sm:px-6 lg:px-8">
        <img
          class="hidden lg:block absolute top-0 right-0 transform -translate-x-3/4 translate-y-1 opacity-50"
          src="/images/france.svg"
          width="904"
          alt=""
        />

        <div class="relative">
          <h2
            class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
          >
            Choisissez votre domaine d’action
          </h2>
          <p
            class="mt-4 max-w-5xl mx-auto text-center text-xl leading-7 text-gray-500"
          >
            Avec la Réserve Civique, soutenez de grandes causes dans votre
            territoire
          </p>
        </div>

        <div class="relative mt-12 mb-8">
          <div class="my-6 grid grid-cols-2 gap-3 md:grid-cols-4 lg:my-12">
            <div
              class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
            >
              <img
                class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                style="width: 40px"
                src="/images/covid.svg"
                alt=""
              />
              <span class="font-semibold">Mobilisation Covid19</span>
            </div>

            <div
              class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
            >
              <img
                class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                style="width: 40px"
                src="/images/sante.svg"
                alt=""
              />
              <span class="font-semibold text-gray-800">Santé pour tous</span>
            </div>

            <div
              class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
            >
              <img
                class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                style="width: 40px"
                src="/images/securite.svg"
                alt=""
              />
              <span class="font-semibold text-gray-800"
                >Prévention et protection</span
              >
            </div>

            <div
              class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
            >
              <img
                class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                style="width: 40px"
                src="/images/education.svg"
                alt=""
              />
              <span class="font-semibold text-gray-800"
                >Education pour tous</span
              >
            </div>
            <div
              class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
            >
              <img
                class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                style="width: 40px"
                src="/images/sport.svg"
                alt=""
              />
              <span class="font-semibold text-gray-800">Sport pour tous </span>
            </div>

            <div
              class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
            >
              <img
                class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                style="width: 40px"
                src="/images/environnement.svg"
                alt=""
              />
              <span class="font-semibold text-gray-800"
                >Protection de la nature
              </span>
            </div>

            <div
              class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
            >
              <img
                class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                style="width: 40px"
                src="/images/art.svg"
                alt=""
              />
              <span class="font-semibold text-gray-800"
                >Art & Culture pour tous
              </span>
            </div>

            <div
              class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
            >
              <img
                class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                style="width: 40px"
                src="/images/vivre-ensemble.svg"
                alt=""
              />
              <span class="font-semibold text-gray-800"
                >Solidarité et insertion
              </span>
            </div>
            <div
              class="col-span-1 lg:col-start-2 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
            >
              <img
                class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                style="width: 40px"
                src="/images/memoire.svg"
                alt=""
              />
              <span class="font-semibold text-gray-800"
                >Mémoire et citoyenneté
              </span>
            </div>
            <div
              class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
            >
              <img
                class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                style="width: 40px"
                src="/images/cooperation.svg"
                alt=""
              />
              <span class="font-semibold text-gray-800"
                >Coopération internationale
              </span>
            </div>
          </div>
        </div>

        <div v-if="!loading" class="relative mx-auto mt-12 mb-8 px-4">
          <div
            v-if="statistics.templates.length > 0"
            class="mb-16 text-center text-base font-semibold uppercase text-gray-500 tracking-wider"
          >
            Parmi les types de missions populaires
          </div>

          <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16"
          >
            <div v-for="template in statistics.templates" :key="template.id">
              <div
                class="inline-block bg-primary rounded-md p-3 text-center mb-5"
              >
                <img alt="" :src="template.image" style="width: 28px" />
              </div>
              <div class="text-lg font-medium text-gray-900">
                {{ template.title }}
              </div>
              <div class="mt-2 text-base text-gray-500">
                {{ template.subtitle }}
              </div>
            </div>
          </div>
        </div>

        <div v-if="!loading" class="relative mx-auto my-8 px-4">
          <div v-if="statistics.templates.length > 0">
            <div
              class="mb-16 text-center text-base font-semibold uppercase text-gray-500 tracking-wider"
            >
              Retrouvez toutes les missions disponibles dans votre commune
            </div>
            <div class="flex flex-wrap items-center justify-center">
              <div
                v-for="(city, key) in statistics.cities"
                :key="key"
                class="inline-flex mx-2 px-4 mb-6 py-2 rounded-full text-md font-semibold shadow-md tracking-wide uppercase bg-white text-gray-800 hover:bg-gray-50"
              >
                <router-link
                  :to="`/missions?query=${
                    city.name
                  }&refinementList[department_name][0]=${$options.filters.fullDepartmentFromValue(
                    collectivity.department
                  )}`"
                  >{{ city.name }}</router-link
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="search" class="bg-primary">
      <div class="container mx-auto py-12 pt-16 px-4 sm:py-16 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto text-center">
          <h2
            class="text-3xl leading-9 font-extrabold text-white sm:text-4xl sm:leading-10"
          >
            <div>{{ collectivity.name }}</div>
            <span class="font-bold"
              >Trouvez une mission dans le département</span
            >
          </h2>
          <p class="text-xl leading-8 text-indigo-200 mt-2">
            <router-link to="/regles-de-securite" target="_blank">
              Consulter les règles de sécurité ›
            </router-link>
          </p>

          <dl
            v-if="!loading"
            class="mt-12 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8"
          >
            <div class="flex flex-col">
              <dd class="text-5xl leading-none font-bold text-white">
                {{ statistics.volontaires_count | formatNumber }}
              </dd>
              <dt class="mt-2 text-lg font-medium text-white">Réservistes</dt>
            </div>
            <div class="flex flex-col mt-10 sm:mt-0">
              <dd class="text-5xl leading-none font-bold text-white">
                {{ statistics.structures_count | formatNumber }}
              </dd>
              <dt class="mt-2 text-lg font-medium text-white">Organisations</dt>
            </div>

            <div class="flex flex-col mt-10 sm:mt-0">
              <dd class="text-5xl leading-none font-bold text-white">
                {{ statistics.participations_count | formatNumber }}
              </dd>
              <dt class="mt-2 text-lg font-medium text-white">
                Mises en relation
              </dt>
            </div>
          </dl>
        </div>

        <MissionsSearchOld
          :query-filters="`department:${collectivity.department}`"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { getCollectivityStatistics } from '@/api/app'
import MissionsSearchOld from '@/components/MissionsSearchOld'

export default {
  name: 'FrontCollectivityDepartment',
  metaInfo() {
    return {
      title: this.collectivity.name
        ? this.collectivity.name +
          ' | Trouvez des missions de bénévolat près de chez vous'
        : '',
      meta: [
        {
          name: 'description',
          content:
            'Rejoignez la Réserve Civique et trouvez une mission de bénévolat auprès d’une organisation publique ou associative. Plus de 320 000 bénévoles déjà inscrits',
        },
      ],
    }
  },
  components: {
    MissionsSearchOld,
  },
  props: {
    collectivity: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      statistics: null,
    }
  },
  created() {
    this.loading = true
    getCollectivityStatistics(this.collectivity.id).then((response) => {
      this.statistics = { ...response.data }
      this.loading = false
    })
  },
  methods: {},
}
</script>
