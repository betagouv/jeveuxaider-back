<template>
  <div>
    <div class="relative">
      <img
        :src="banner"
        :alt="collectivity.title"
        class="absolute object-cover object-center w-full h-full"
      />

      <div
        :class="[
          'bg-primary',
          'absolute',
          'inset-0',
          { 'opacity-75': collectivity.banner && collectivity.banner.large },
          {
            'opacity-25':
              !collectivity.banner ||
              (collectivity.banner && !collectivity.banner.large),
          },
        ]"
      />

      <div class="relative pt-1 pb-12 lg:py-12">
        <div class="container mx-auto px-4">
          <div class="py-8 text-center sm:text-left">
            <h2
              class="text-4xl max-w-4xl leading-none font-bold text-white sm:text-5xl md:text-6xl"
            >
              <template v-if="collectivity.title">{{
                collectivity.title
              }}</template>
              <template v-else
                >Rejoignez la Réserve Civique dans votre collectivité</template
              >
            </h2>

            <p
              class="mt-5 text-base text-gray-100 max-w-xl sm:text-lg md:text-xl"
            >
              <template v-if="collectivity.description">{{
                collectivity.description
              }}</template>
              <template v-else>
                <b>{{ collectivity.name }}</b> • Trouvez une mission d'intérêt
                général qui vous ressemble et faites vivre l’engagement local.
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

    <div class="py-16 bg-gray-50 overflow-hidden shadow-lg">
      <div class="relative container mx-auto px-6 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center">
          <img
            v-if="logo"
            class="max-h-20 mb-3"
            :src="logo"
            height="auto"
            width="auto"
          />
          <h3
            class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
          >
            <span class="text-blue-900">{{ collectivity.name }}</span> : faites
            vivre l'engagement !
          </h3>
          <p
            class="mt-4 max-w-2xl mx-auto text-center text-xl leading-7 text-gray-500"
          >
            Soutenez l'action des associations, collectivités et organisations
            locales à travers différents types de missions.
          </p>
        </div>
      </div>
    </div>

    <div class="bg-white z-10">
      <div>
        <div class="grid grid-cols-2 gap-0 md:grid-cols-6 lg:grid-cols-6">
          <div
            v-for="index in 6"
            :key="index"
            class="col-span-1 justify-center md:col-span-2 lg:col-span-1"
          >
            <img :src="illustration(index)" height="auto" width="auto" />
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
            Rejoignez le mouvement #RéserveCivique
          </h2>
          <p class="text-xl max-w-2xl mx-auto leading-8 text-indigo-200 mt-2">
            Partout en France, des milliers de réservistes, organisations
            publiques et associations sont déjà mobilisés sur le terrain.
          </p>
          <!-- <dl
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
          </dl> -->
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
// import { getCollectivityStatistics } from '@/api/app'
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
    logo() {
      return this.collectivity.logo && this.collectivity.logo.thumb
        ? this.collectivity.logo.thumb
        : null
    },
    banner() {
      return this.collectivity.banner && this.collectivity.banner.large
        ? this.collectivity.banner.large
        : `/images/collectivites/cover_default.jpg`
    },
  },
  created() {
    // this.loading = true
    // getCollectivityStatistics(this.collectivity.id).then((response) => {
    //   this.statistics = { ...response.data }
    //   this.loading = false
    // })
  },
  methods: {
    illustration(key) {
      return this.collectivity[`image_${key}`] &&
        this.collectivity[`image_${key}`].large
        ? this.collectivity[`image_${key}`].large
        : `/images/collectivites/defaut_${key}.jpg`
    },
  },
}
</script>
