<template>
  <div>
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
              class="text-4xl max-w-4xl leading-none font-bold text-white sm:text-5xl md:text-6xl"
            >
              Rejoignez la Réserve Civique dans votre collectivité
            </h2>

            <p
              class="mt-5 text-base text-gray-100 max-w-xl sm:text-lg md:text-xl"
            >
              <b>{{ collectivity.title }}</b> • Trouvez une mission d'intérêt
              général qui vous ressemble et faites vivre l’engagement local.
            </p>

            <div
              class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start"
            >
              <div class="text-center">
                <p class="text-xs leading-6 font-medium text-white">
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
                <p class="text-xs leading-6 font-medium text-white">
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

    <div v-if="!loading" class="py-20 bg-gray-50 overflow-hidden shadow-lg">
      <div
        v-if="statistics.templates.length > 0"
        class="relative container mx-auto px-6 sm:px-6 lg:px-8"
      >
        <div class="relative">
          <h3
            class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
          >
            <span class="text-blue-900">{{ collectivity.title }}</span> : faites
            vivre l'engagement !
          </h3>
          <p
            class="mt-4 max-w-2xl mx-auto text-center text-xl leading-7 text-gray-500"
          >
            Soutenez l'action des associations, collectivités et structures
            locales à travers différents types de missions :
          </p>
        </div>

        <div class="relative mx-auto my-8 px-4">
          <div
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
      </div>
    </div>

    <div class="bg-white z-10">
      <div>
        <div class="grid grid-cols-2 gap-0 md:grid-cols-6 lg:grid-cols-6">
          <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
            <img
              :src="`/images/collectivites/${collectivity.slug}-1.jpg`"
              height="auto"
              width="auto"
            />
          </div>
          <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
            <img
              :src="`/images/collectivites/${collectivity.slug}-2.jpg`"
              height="auto"
              width="auto"
            />
          </div>
          <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
            <img
              :src="`/images/collectivites/${collectivity.slug}-3.jpg`"
              height="auto"
              width="auto"
            />
          </div>
          <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
            <img
              :src="`/images/collectivites/${collectivity.slug}-4.jpg`"
              height="auto"
              width="auto"
            />
          </div>
          <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
            <img
              :src="`/images/collectivites/${collectivity.slug}-5.jpg`"
              height="auto"
              width="auto"
            />
          </div>

          <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
            <img
              :src="`/images/collectivites/${collectivity.slug}-6.jpg`"
              height="auto"
              width="auto"
            />
          </div>
        </div>
      </div>
    </div>

    <div class="bg-primary">
      <div class="container mx-auto py-12 pt-16 px-4 sm:py-16 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto text-center">
          <h2
            class="text-3xl leading-9 font-extrabold text-white sm:text-4xl sm:leading-10"
          >
            Rejoignez le mouvement #RéserveCivique
          </h2>
          <p class="text-xl max-w-2xl mx-auto leading-8 text-indigo-200 mt-2">
            Partout en France, des milliers de réservistes, organisations
            publiques et associations sont déjà mobilisés sur le terrain.
          </p>
          <dl
            v-if="!loading"
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
        <missions-search :query-filters="zipsFilter"></missions-search>
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
          <span class="text-blue-800">bénévoles</span> ?
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
                        structureId: $store.getters.structure_as_responsable.id,
                      },
                    }
                  : '/register/responsable'
              "
              class="inline-flex items-center justify-center px-7 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
            >
              Rejoignez la Réserve Civique
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getCollectivityStatistics } from '@/api/app'
import MissionsSearch from '@/components/MissionsSearch'

export default {
  name: 'FrontCollectivityCommune',
  components: {
    MissionsSearch,
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
  computed: {
    zipsFilter() {
      let zips = this.collectivity.zips.map((zip) => 'zip:' + zip)
      return zips.join(' OR ')
    },
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
