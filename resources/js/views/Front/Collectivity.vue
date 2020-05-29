<template>
  <div>
    <AppHeader :show-menu="false">
      <template v-slot:menu>
        <div class="hidden sm:block ml-2 mr-auto w-auto order-2">
          <div
            class="text-xl md:text-2xl font-medium text-gray-500 leading-none"
          >
            • {{ collectivity.title }}
          </div>
        </div>
      </template>
    </AppHeader>
    <div class="relative">
      <img
        v-if="collectivity.image && collectivity.image.large"
        :src="collectivity.image.large"
        :alt="collectivity.title"
        class="absolute object-cover object-center w-full h-full"
      />

      <div
        :class="[
          'bg-primary',
          'absolute',
          'inset-0',
          { 'opacity-75': collectivity.image },
        ]"
      />

      <div class="relative pt-1 pb-12 lg:py-12">
        <div class="container mx-auto px-4">
          <div class="py-8 text-center sm:text-left">
            <h2
              class="text-4xl leading-none font-bold text-white sm:text-5xl md:text-6xl"
            >
              Rejoignez la Réserve Civique <br />dans votre département
            </h2>

            <p
              class="mt-5 text-base text-gray-100 max-w-xl sm:text-lg md:text-xl"
            >
              <b>{{ collectivity.title }}</b> • Votre organisation a besoin de
              renfort localement ? Vous souhaitez vous engager bénévolement au
              plus près de chez vous ? Rejoignez la Réserve Civique dans votre
              département.
            </p>

            <div
              class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start"
            >
              <div class="text-center">
                <p class="pb-1 text-xs leading-6 font-medium text-white">
                  Je veux aider
                </p>
                <router-link
                  to="/register/volontaire"
                  class="shadow-lg w-full flex items-center justify-center px-10 py-3 text-base leading-6 font-medium rounded-full bg-white text-blue-800 hover:bg-gray-100 hover:bg-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-15"
                >
                  Devenir réserviste
                </router-link>
              </div>
              <div class="mt-3 sm:mt-0 sm:ml-6 text-center">
                <p class="pb-1 text-xs leading-6 font-medium text-white">
                  Mon organisation a besoin de renfort
                </p>
                <router-link
                  :to="
                    $store.getters.isLogged &&
                    $store.getters.contextRole == 'responsable'
                      ? {
                          name: 'MissionFormAdd',
                          params: {
                            structureId:
                              $store.getters.structure_as_responsable.id,
                          },
                        }
                      : '/register/responsable'
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
          <h3
            class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
          >
            Choisissez votre domaine d’action
          </h3>
          <p
            class="mt-4 max-w-5xl mx-auto text-center text-xl leading-7 text-gray-500"
          >
            Avec la Réserve Civique, soutenez de grandes causes dans votre
            territoire
          </p>
        </div>

        <div class="relative my-8">
          <div class="mx-auto py-12 lg:py-8">
            <p
              class="text-center text-base leading-6 font-semibold uppercase text-gray-500 tracking-wider"
            >
              Trouvez votre domaine d'action
            </p>
            <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-4 lg:mt-8">
              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
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
                  style="width: 40px;"
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
                  style="width: 40px;"
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
                  style="width: 40px;"
                  src="/images/education.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800"
                  >Education pour tous</span
                >
              </div>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-3 md:grid-cols-4 lg:mt-3">
              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/sport.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800"
                  >Sport pour tous
                </span>
              </div>

              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
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
                  style="width: 40px;"
                  src="/images/memoire.svg"
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
                  style="width: 40px;"
                  src="/images/vivre-ensemble.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800">Vivre ensemble </span>
              </div>
            </div>
          </div>
        </div>

        <div class="relative mx-auto my-8 px-4">
          <div
            class="mb-16 text-center text-base font-semibold uppercase text-gray-500 tracking-wider"
          >
            Parmi les types de missions populaires
          </div>

          <div
            v-if="!loading"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16"
          >
            <div v-for="template in statistics.templates" :key="template.id">
              <div
                class="inline-block bg-primary rounded-md p-3 text-center mb-5"
              >
                <img class :src="template.image" style="width: 28px;" />
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

        <div class="relative mx-auto my-8 px-4">
          <div
            class="mb-16 text-center text-base font-semibold uppercase text-gray-500 tracking-wider"
          >
            Retrouvez toutes les missions disponibles dans votre commune
          </div>
          <div
            v-if="!loading"
            class="pb-16 flex flex-wrap items-center justify-center"
          >
            <div
              v-for="(city, key) in statistics.cities"
              :key="key"
              class="inline-flex mx-2 px-4 mb-6 py-2 rounded-full text-md font-semibold shadow-md tracking-wide uppercase bg-white text-gray-800 hover:bg-gray-50"
            >
              <router-link
                :to="`/missions?query=${
                  city.name
                }&menu%5Bdepartment_name%5D=${$options.filters.fullDepartmentFromValue(
                  collectivity.department
                )}`"
                >{{ city.name }}</router-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="container mx-auto px-4">
      <div class="py-16">
        <div class="text-center">
          <h2
            class="text-3xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-14 leading-none"
          >
            <div class="text-blue-900">{{ collectivity.title }}</div>
            <div>Choisissez votre domaine d’action</div>
          </h2>

          <p
            v-if="!loading"
            class="mt-4 mx-auto max-w-3xl text-xl pb-8 text-gray-500 text-center"
          >
            Sur l'ensemble du territoire français,
            <b>{{ statistics.national.volontaires_count | formatNumber }}</b>
            réservistes et
            <b>{{ statistics.national.structures_count | formatNumber }}</b>
            structures publiques et associatives ont déjà rejoint la Réserve
            Civique.
          </p>

          <dl
            v-if="!loading"
            class="mt-2 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8"
          >
            <div class="flex flex-col">
              <dd class="text-5xl leading-none font-bold text-gray-800">
                {{ statistics.volontaires_count | formatNumber }}
              </dd>
              <dt class="mt-2 text-lg font-medium text-gray-800">
                Réservistes
              </dt>
            </div>
            <div class="flex flex-col mt-10 sm:mt-0">
              <dd class="text-5xl leading-none font-bold text-gray-800">
                {{ statistics.structures_count | formatNumber }}
              </dd>
              <dt class="mt-2 text-lg font-medium text-gray-800">Structures</dt>
            </div>

            <div class="flex flex-col mt-10 sm:mt-0">
              <dd class="text-5xl leading-none font-bold text-gray-800">
                {{ statistics.participations_count | formatNumber }}
              </dd>
              <dt class="mt-2 text-lg font-medium text-gray-800">
                Mises en relation
              </dt>
            </div>
          </dl>

          
        </div>
      </div>
    </div> -->

    <div class="bg-primary">
      <div class="container mx-auto py-12 pt-16 px-4 sm:py-16 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto text-center">
          <h2
            class="text-3xl leading-9 font-extrabold text-white sm:text-4xl sm:leading-10"
          >
            <div>{{ collectivity.title }}</div>
            <span class="font-bold"
              >Trouvez une mission dans le département</span
            >
          </h2>
          <p class="text-xl leading-8 text-indigo-200 mt-2">
            <router-link to="/regles-de-securite" target="_blank">
              Consulter les règles de sécurité ›
            </router-link>
          </p>
        </div>

        <missions-search
          :facet-filters="[`department: ${collectivity.department}`]"
          :department="collectivity.department"
        ></missions-search>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script>
import { getCollectivity, getCollectivityStatistics } from '@/api/app'
import MissionsSearch from '@/components/MissionsSearch'

export default {
  name: 'FrontCollectivity',
  components: {
    MissionsSearch,
  },
  props: {
    slug: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: true,
      collectivity: {},
      statistics: null,
    }
  },
  created() {
    this.$store.commit('setLoading', true)
    getCollectivity(this.slug)
      .then((response) => {
        this.collectivity = { ...response.data }
        getCollectivityStatistics(this.collectivity.id).then((response) => {
          this.statistics = { ...response.data }
          this.$store.commit('setLoading', false)
          this.loading = false
        })
      })
      .catch(() => {
        this.loading = false
      })
  },
  methods: {},
}
</script>
