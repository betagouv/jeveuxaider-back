<template>
  <div v-if="thematique">
    <AppHeader :show-menu="false">
      <template v-slot:menu>
        <div class="hidden sm:block ml-2 mr-auto w-auto order-2">
          <div
            class="text-xl md:text-2xl font-medium text-gray-500 leading-none"
          >
            • {{ thematique.name }}
          </div>
        </div>
      </template>
    </AppHeader>
    <div>
      <div class="relative">
        <img
          v-if="thematique.image && thematique.image.large"
          :src="thematique.image.large"
          :alt="thematique.title"
          class="absolute object-cover object-center w-full h-full"
        />

        <div
          :class="[
            `bg-${thematique.color}`,
            'absolute',
            'inset-0',
            { 'opacity-90': thematique.image },
          ]"
        />

        <div class="relative pt-1 pb-12 lg:py-12">
          <div class="container mx-auto px-4">
            <div class="py-8 text-center sm:text-left">
              <h2
                class="text-4xl max-w-4xl leading-none font-bold text-white sm:text-5xl md:text-6xl"
              >
                Rejoignez la Réserve Civique dans votre domaine d'action
              </h2>

              <p
                class="mt-5 text-base text-gray-100 max-w-xl sm:text-lg md:text-xl"
              >
                {{ thematique.description }}
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
                    :class="`text-${thematique.color}`"
                    class="shadow-lg w-full flex items-center justify-center px-10 py-3 text-base leading-6 font-medium rounded-full bg-white hover:bg-gray-100 hover:bg-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-15"
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
          <div class="relative">
            <h3
              class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
            >
              <span :class="`text-${thematique.color}`">{{
                thematique.name
              }}</span>
              : faites vivre l'engagement !
            </h3>
            <p
              class="mt-4 max-w-2xl mx-auto text-center text-xl leading-7 text-gray-500"
            >
              Soutenez l'action des associations, collectivités et structures
              éducatives à travers différents types de missions :
            </p>
          </div>

          <div class="relative mx-auto my-8 px-4">
            <div
              v-if="!$store.getters.loading"
              class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16"
            >
              <div v-for="template in statistics.templates" :key="template.id">
                <div
                  :class="`bg-${thematique.color}`"
                  class="inline-block rounded-md p-3 text-center mb-5"
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
        </div>
      </div>

      <div v-if="!$store.getters.loading" :class="`bg-${thematique.color}`">
        <div
          class="container mx-auto py-12 pt-16 px-4 sm:py-16 sm:px-6 lg:px-8"
        >
          <div class="max-w-6xl mx-auto text-center">
            <h2
              class="text-3xl leading-9 font-extrabold text-white sm:text-4xl sm:leading-10"
            >
              Rejoignez le mouvement #RéserveCivique
            </h2>
            <p class="text-xl max-w-2xl mx-auto leading-8 text-gray-200 mt-2">
              Sur l’ensemble du territoire français, des milliers de bénévoles
              et d’organisations se sont déjà ralliés à la Réserve Civique pour
              soutenir ce domaine d'action.
            </p>
            <dl
              class="mt-12 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8"
            >
              <div class="flex flex-col">
                <dd class="text-5xl leading-none font-bold text-white">
                  {{ statistics.volontaires_count | formatNumber }}
                </dd>
                <dt class="mt-2 text-lg font-medium text-white">
                  Réservistes
                </dt>
              </div>
              <div class="flex flex-col mt-10 sm:mt-0">
                <dd class="text-5xl leading-none font-bold text-white">
                  {{ statistics.structures_count | formatNumber }}
                </dd>
                <dt class="mt-2 text-lg font-medium text-white">
                  Structures
                </dt>
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
          <missions-search
            :color="thematique.color"
            :query-filters="`domaines:&quot;${thematique.domaine.name.fr}&quot;`"
          ></missions-search>
        </div>
      </div>

      <div class="bg-gray-50 border-b border-gray-200">
        <div
          class="container mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between"
        >
          <h2
            class="text-3xl leading-9 font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
          >
            Votre organisation a besoin de
            <span :class="`text-${thematique.color}`">bénévoles</span> ?
          </h2>
          <div class="mt-8 flex lg:flex-shrink-0 lg:mt-0">
            <div class="inline-flex rounded-full shadow">
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
                :class="`bg-${thematique.color}`"
                class="inline-flex items-center justify-center px-7 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
              >
                Rejoignez la Réserve Civique
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
    <AppFooter />
  </div>
</template>

<script>
import { getThematique, getThematiqueStatistics } from '@/api/app'
import MissionsSearch from '@/components/MissionsSearch'

export default {
  name: 'FrontThematique',
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
      thematique: {},
      statistics: null,
    }
  },
  created() {
    this.$store.commit('setLoading', true)
    getThematique(this.slug)
      .then((response) => {
        this.thematique = { ...response.data }
        getThematiqueStatistics(this.thematique.id).then((response) => {
          this.statistics = { ...response.data }
          this.$store.commit('setLoading', false)
        })
      })
      .catch(() => {})
  },
  methods: {},
}
</script>
