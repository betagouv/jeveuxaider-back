<template>
  <div>
    <div class="relative">
      <img
        v-if="collectivity.image && collectivity.image.large"
        :src="collectivity.image.large"
        :alt="collectivity.title"
        class="absolute object-cover object-center w-full h-full"
      />

      <div :class="['bg-blue-900', 'absolute', 'inset-0', {'opacity-75': collectivity.image}]"></div>

      <div class="relative pt-1 pb-12 lg:py-12">
        <AppHeader background="bg-transparent" border :showMenu="false">
          <template v-slot:menu>
            <div class="hidden sm:block ml-2 mr-auto w-auto order-2">
              <div
                class="text-xl md:text-2xl font-medium text-white leading-none"
              >• {{ collectivity.title }}</div>
            </div>
          </template>
        </AppHeader>
        <div class="container mx-auto px-4">
          <div class="py-8 text-center sm:text-left">
            <h2 class="text-4xl leading-none font-bold text-white sm:text-5xl md:text-6xl">
              Engagez-vous dans
              <br class="hidden sm:block" />
              <span class="text-white">votre département</span>
            </h2>

            <p class="mt-5 text-base text-gray-100 max-w-xl sm:text-lg md:text-xl">
              <!-- Le département <b>{{ collectivity.title }}</b> vous propose un espace d'engagement ouvert à tous, que vous soyez bénévole dans l’âme ou bien un acteur local du monde associatif. -->

              <b>{{ collectivity.title }}</b> • Votre structure a besoin de renforts ? Vous souhaitez vous engager bénévolement au plus près de chez vous ? Rejoignez la Réserve Civique dans votre département.
            </p>

            <div class="mt-5 sm:mt-8 sm:flex sm:justify-start">
              <div class="text-center">
                <div
                  class="pb-1 text-sm font-medium text-gray-100"
                >Structure publique ou associative</div>
                <router-link to="/register/responsable" class="btn-primary">
                  <span class="text-lg font-bold">Proposer une mission</span>
                </router-link>
              </div>
              <div class="mt-3 sm:mt-0 sm:ml-6 text-center">
                <div class="pb-1 text-sm font-medium text-gray-100">Bénévole</div>
                <router-link to="/register/volontaire" class="btn-white">
                  <span class="text-lg font-bold">Je veux aider</span>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4">
      <div class="py-16">
        <div class="text-center">
          <h2
            class="text-3xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-14 leading-none"
          >
            <div class="text-blue-900">{{ collectivity.title }}</div>L'engagement en quelques chiffres
          </h2>
          <p v-if="!loading" class="mt-4 mx-auto max-w-3xl text-xl pb-8 text-gray-500 text-center">
            Sur l'ensemble du territoire français,
            <b>{{ statistics.national.volontaires_count|formatNumber }}</b> réservistes et
            <b>{{ statistics.national.structures_count|formatNumber }}</b> structures publiques et associatives ont déjà rejoint la Réserve Civique.
          </p>

          <dl
            v-if="!loading"
            class="mt-2 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8"
          >
            <div class="flex flex-col">
              <dd
                class="text-5xl leading-none font-bold text-gray-800"
              >{{ statistics.volontaires_count|formatNumber }}</dd>
              <dt class="mt-2 text-lg font-medium text-gray-800">Réservistes</dt>
            </div>
            <div class="flex flex-col mt-10 sm:mt-0">
              <dd
                class="text-5xl leading-none font-bold text-gray-800"
              >{{ statistics.structures_count|formatNumber }}</dd>
              <dt class="mt-2 text-lg font-medium text-gray-800">Structures</dt>
            </div>

            <div class="flex flex-col mt-10 sm:mt-0">
              <dd
                class="text-5xl leading-none font-bold text-gray-800"
              >{{ statistics.participations_count|formatNumber }}</dd>
              <dt class="mt-2 text-lg font-medium text-gray-800">Mises en relation</dt>
            </div>
          </dl>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4">
      <div
        class="mb-16 text-center text-base font-semibold uppercase text-gray-500 tracking-wider"
      >Parmi les domaines d'actions populaires</div>

      <div v-if="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
        <div v-for="(domain, key) in statistics.domains" :key="key">
          <div class="inline-block bg-blue-900 rounded-md p-3 text-center mb-5">
            <img class :src="$options.filters.domainIcon(domain.key)" style="width:28px;" />
          </div>
          <div class="text-lg font-medium text-gray-900">{{ domain.key | cleanDomaineAction }}.</div>
          <div class="mt-2 text-base text-gray-500">{{ domain.name }}</div>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4">
      <div
        class="mb-16 text-center text-base font-semibold uppercase text-gray-500 tracking-wider"
      >Retrouvez toutes les missions disponibles dans votre commune</div>
      <div class="pb-16 flex flex-wrap items-center justify-center" v-if="!loading">
        <div
          v-for="(city, key) in statistics.cities"
          :key="key"
          class="inline-flex mx-2 px-4 mb-6 py-2 rounded-full text-md font-semibold shadow-md tracking-wide uppercase bg-white text-gray-800 hover:bg-gray-50"
        >
          <router-link
            :to="`/missions?query=${city.name}&menu%5Bdepartment_name%5D=${$options.filters.fullDepartmentFromValue(collectivity.department)}`"
          >{{ city.name }}</router-link>
        </div>
      </div>
    </div>

    <div class="bg-blue-900">
      <div class="container mx-auto py-12 pt-16 px-4 sm:py-16 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto text-center">
          <h2 class="text-3xl leading-9 font-extrabold text-white sm:text-4xl sm:leading-10">
            <div>{{ collectivity.title }}</div>
            <span class="font-bold">Trouvez une mission dans le département</span>
          </h2>
          <p class="text-xl leading-8 text-indigo-200 mt-2">
            <router-link to="/regles-de-securite" target="_blank">Consulter les règles de sécurité ›</router-link>
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
import { getCollectivity, getCollectivityStatistics } from "@/api/app";
import MissionsSearch from "@/components/MissionsSearch";

export default {
  name: "FrontCollectivity",
  components: {
    MissionsSearch
  },
  props: {
    slug: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      loading: true,
      collectivity: {},
      statistics: null
    };
  },
  created() {
    this.$store.commit("setLoading", true);
    getCollectivity(this.slug)
      .then(response => {
        this.collectivity = { ...response.data };
        getCollectivityStatistics(this.collectivity.id).then(response => {
          console.log(response.data)
          this.statistics = { ...response.data };
          this.$store.commit("setLoading", false);
          this.loading = false;
        });
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {}
};
</script>
