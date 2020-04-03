<template>
  <div>
    <AppHeader />

    <div class="bg-blue-900 pb-12">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">Collectivité de {{ collectivity.name }}</h1>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="border-t lg:border-t-0 lg:w-1/2 py-8 md:py-16 lg:p-16">
        <div class="uppercase pb-2 mt-3 text-xl text-gray-500 sm:mt-5">
          Crise sanitaire du
          <b>COVID-19</b>
        </div>
        <div class="sm:text-center lg:text-left">
          <h2
            class="text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl"
          >
            Engagez-vous
            <br />pour
            <span class="text-blue-500">{{ collectivity.name }}</span>
          </h2>
          <p
            class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-2xl md:max-w-3xl"
          >
            Face à l’épidémie de Covid-19
            le Gouvernement appelle à la mobilisation
            générale des solidarités.
          </p>

          <div class="mt-6 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-2 sm:gap-2">
            <div>
              <div
                class="mt-2 pb-1 text-sm leading-6 font-medium text-gray-500"
              >Je suis une structure associative</div>
              <router-link
                to="/register/responsable"
                class="btn-primary w-full"
                style="max-width:320px;"
              >
                <span class="text-lg font-bold">Proposer une mission</span>
              </router-link>
            </div>
            <div>
              <div class="mt-2 pb-1 text-sm leading-6 font-medium text-gray-500">Je suis volontaire</div>

              <router-link
                to="/register/volontaire"
                class="btn-secondary w-full"
                style="max-width:320px;"
              >
                <span class="text-lg font-bold">Je veux aider</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
      <div class="hidden lg:block lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/hotel-de-ville-collectivite.jpg"
        />
      </div>
    </div>

    <div class="bg-blue-900">
      <div class="container mx-auto py-12 pt-16 px-4 sm:py-16 sm:px-6 lg:px-8 lg:py-12 lg:pt-20">
        <div class="max-w-6xl mx-auto text-center">
          <h2 class="text-3xl leading-9 font-extrabold text-white sm:text-4xl sm:leading-10">
            Trouvez une mission de bénévolat à
            <span>{{ collectivity.name }}</span>
          </h2>
          <p class="text-xl leading-8 text-indigo-200 mt-2">
            <router-link to="/regles-de-securite">Consulter les règles de sécurité ›</router-link>
          </p>
        </div>  

        <template v-if="modeLigth">
          <div class="-mt-32">
            <div class="container mx-auto px-4 my-12">
              <div class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16">
                Les organisations en première ligne face à la crise proposent
                actuellement leurs missions prioritaires.
                <br />Elles seront mises en ligne très prochainement.
                <br />Revenez demain pour les découvrir !
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <ais-instant-search :search-client="searchClient" :index-name="indexName">
            <ais-configure :hits-per-page.camel="20" />
            <div class="bg-blue-900 pb-32">
              <div class="container mx-auto px-4">
                <div
                  :class="[
                { 'py-10': missionsAreReady },
                { 'pt-10': !missionsAreReady }
              ]"
                ></div>
                <div
                  class="filters md:flex md:rounded-lg md:shadow md:bg-white"
                  v-if="missionsAreReady"
                >
                  <ais-search-box
                    class="flex-1"
                    autofocus
                    placeholder="Mots-clés, ville, code postal, etc."
                  />
                  <ais-menu-select
                    class="flex-1"
                    attribute="domaine_action"
                    :transform-items="transformItems"
                  >
                    <el-select
                      v-model="filters.domaine_action"
                      slot-scope="{ items, canRefine, refine }"
                      :disabled="!canRefine"
                      @change="refine($event)"
                      placeholder="Domaines d'actions"
                      popper-class="domaines-actions"
                    >
                      <el-option
                        v-for="item in items"
                        :key="item.value"
                        :label="`${$options.filters.cleanDomaineAction(item.label)} (${item.count})`"
                        :selected="item.isRefined"
                        :value="item.value"
                      ></el-option>
                    </el-select>
                  </ais-menu-select>
                  <ais-clear-refinements>
                    <div
                      @click.prevent="handleResetFilters(refine)"
                      slot-scope="{ canRefine, refine }"
                      class="py-2 md:p-4"
                      :class="{
                    'cursor-not-allowed text-gray-400 hidden md:block': !canRefine,
                    'cursor-pointer text-blue-300  md:text-primary block': canRefine
                  }"
                    >Réinitialiser</div>
                  </ais-clear-refinements>
                </div>
              </div>
            </div>

            <div ref="resultsWrapper" class="-mt-32">
              <div class="container mx-auto px-4 my-12">
                <div
                  v-if="!missionsAreReady"
                  class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16 mb-16"
                >Missions en cours de validation...</div>

                <ais-state-results v-else>
                  <template slot-scope="{ hits, nbHits, page, nbPages, hitsPerPage }">
                    <template v-if="hits.length > 0">
                      <ais-hits>
                        <div slot="item" slot-scope="{ item }">
                          <router-link
                            class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out"
                            :to="`/missions/${item.id}`"
                          >
                            <div class="p-4 sm:p-6 md:p-8">
                              <div class="flex items-center">
                                <div
                                  class="hidden sm:block flex-shrink-0 bg-blue-900 rounded-md p-3 text-center"
                                >
                                  <img
                                    class
                                    :src="iconDomain(item.domaine_action)"
                                    style="width:28px;"
                                  />
                                </div>
                                <div class="min-w-0 flex-1 sm:pl-4">
                                  <div
                                    class="flex items-center justify-between flex-wrap sm:flex-no-wrap -m-2"
                                  >
                                    <div class="m-2 min-w-0 flex-shrink">
                                      <div
                                        class="text-sm leading-5 uppercase font-medium text-gray-500 truncate"
                                        v-text="item.type"
                                      ></div>
                                      <div
                                        class="text-sm md:text-base lg:text-lg xl:text-xl font-semibold text-gray-900 truncate"
                                        v-text="item.name"
                                      ></div>
                                    </div>

                                    <div
                                      v-if="
                                    item.has_places_left && item.places_left > 0
                                  "
                                      class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
                                      style="background:#31c48d;"
                                    >
                                      <template>
                                        {{ item.places_left | formatNumber }}
                                        {{
                                        item.places_left
                                        | pluralize([
                                        "volontaire recherché",
                                        "volontaires recherchés"
                                        ])
                                        }}
                                      </template>
                                    </div>
                                    <div
                                      v-else
                                      class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
                                      style="background:#d2d6dc;"
                                    >Complet</div>
                                  </div>
                                </div>
                              </div>

                              <div class="mt-4 flex items-start text-sm text-gray-500">
                                <svg
                                  class="flex-shrink-0 mr-2 h-5 w-5 text-gray-400"
                                  fill="currentColor"
                                  viewBox="0 0 20 20"
                                >
                                  <path
                                    fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"
                                  />
                                </svg>
                                <span
                                  v-text="
                                `${item.city} (${item.department}) - ${item.structure.name}`
                              "
                                ></span>
                              </div>
                            </div>
                          </router-link>
                        </div>
                      </ais-hits>

                      <div class="px-4 sm:px-6 md:px-8">
                        <div v-show="false" class="text-sm text-gray-700">
                          <span
                            v-html="
                          formatNbResults(nbHits, page, nbPages, hitsPerPage)
                        "
                          ></span>
                          {{
                          nbHits
                          | pluralize([
                          "mission disponible",
                          "missions disponibles"
                          ])
                          }}
                        </div>
                        <div class="pagination w-full border-b-2 border-transparent">
                          <ais-pagination :padding="2" @page-change="scrollToTop">
                            <ul
                              slot-scope="{
                            currentRefinement,
                            nbPages,
                            pages,
                            isFirstPage,
                            isLastPage,
                            refine,
                            createURL
                          }"
                              class="ais-Pagination-list"
                            >
                              <li
                                class="ais-Pagination-item ais-Pagination-item--previousPage"
                                :class="[
                              { 'ais-Pagination-item--disabled': isFirstPage }
                            ]"
                              >
                                <a
                                  :href="createURL(currentRefinement - 1)"
                                  @click.prevent="!isFirstPage ? refine(currentRefinement - 1) : null"
                                  class="ais-Pagination-link"
                                >
                                  <svg
                                    class="mr-8 h-5 w-5 text-gray-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                  >
                                    <path
                                      fill-rule="evenodd"
                                      d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                      clip-rule="evenodd"
                                    />
                                  </svg>
                                  <span>Précédente</span>
                                </a>
                              </li>
                              <li
                                v-for="page in pages"
                                :key="page"
                                class="ais-Pagination-item"
                                :class="[
                              {
                                'ais-Pagination-item--selected':
                                  currentRefinement === page
                              }
                            ]"
                              >
                                <a
                                  :href="createURL(page)"
                                  @click.prevent="currentRefinement !== page ? refine(page) : null"
                                  class="ais-Pagination-link"
                                >{{ page + 1 }}</a>
                              </li>
                              <li
                                class="ais-Pagination-item ais-Pagination-item--nextPage"
                                :class="[
                              { 'ais-Pagination-item--disabled': isLastPage }
                            ]"
                              >
                                <a
                                  :href="createURL(currentRefinement + 1)"
                                  @click.prevent="!isLastPage ? refine(currentRefinement + 1) : null"
                                  class="ais-Pagination-link"
                                >
                                  <span>Suivante</span>
                                  <svg
                                    class="ml-8 h-5 w-5 text-gray-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                  >
                                    <path
                                      fill-rule="evenodd"
                                      d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                      clip-rule="evenodd"
                                    />
                                  </svg>
                                </a>
                              </li>
                            </ul>
                          </ais-pagination>
                        </div>
                      </div>
                    </template>

                    <div
                      v-else
                      class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
                    >Pas de résultats.</div>
                  </template>
                </ais-state-results>
              </div>
            </div>
          </ais-instant-search>
        </template>
      </div>
    </div>

    <div class="bg-gray-50 border-b border-gray-200">
      <div class="container mx-auto py-12 px-4 sm:px-6 lg:py-24 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-20 lg:items-center">
          <div>
            <h2 class="text-3xl pb-4 leading-10 font-extrabold text-gray-900 sm:text-4xl">
              A chacun sa
              <span class="text-blue-500">mission</span>
            </h2>
            <p
              class="mt-2 max-w-3xl text-xl pb-8 leading-7 text-gray-500"
            >Contribuez à la lutte contre l’épidémie en menant des 4 types d'actions de solidarité dans votre commune :</p>

            <ul>
              <li class="flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="h-6 w-6 text-green-500"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p
                  class="ml-3 text-lg leading-6 text-gray-700"
                >Je fais les courses de produits essentiels pour mes voisins les plus fragiles.</p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="h-6 w-6 text-green-500"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p
                  class="ml-3 text-lg leading-6 text-gray-700"
                >Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis</p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="h-6 w-6 text-green-500"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p
                  class="ml-3 text-lg leading-6 text-gray-700"
                >Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance</p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="h-6 w-6 text-green-500"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p
                  class="ml-3 text-lg leading-6 text-gray-700"
                >Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)</p>
              </li>
            </ul>
          </div>
          <div class="mt-8 grid grid-cols-2 gap-1 md:grid-cols-3 lg:mt-0 lg:grid-cols-2">
            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/police-nationale.jpg" />
            </div>

            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/restaurants-du-coeur.jpg" />
            </div>
            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/petits-freres-des-pauvres.jpg" />
            </div>
            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/meudon-bien-etre.jpg" />
            </div>
            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/secours-populaire-francais.jpg" />
            </div>
            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/la-source.jpg" />
            </div>
            <div class="pt-6 col-span-2 justify-center inline-flex">
              <router-link
                to="/register/responsable"
                class="inline-flex items-center justify-center shadow-md px-5 py-2 text-sm bg-white leading-6 font-medium rounded-full text-gray-500 hover:bg-gray-50 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                style="max-width:320px;"
              >Ajoutez votre structure</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-gray-50 border-b border-gray-200">
      <div
        class="container mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between"
      >
        <h2
          class="text-3xl leading-9 font-extrabold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
        >
          Votre collectivité a besoin
          <br />de
          <span class="text-blue-500">renforts</span> ?
        </h2>
        <div class="mt-8 flex lg:flex-shrink-0 lg:mt-0">
          <div class="inline-flex rounded-full shadow">
            <router-link
              to="/collectivity/new"
              class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-white bg-blue-500 hover:bg-blue-800 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
              style="max-width:320px;"
            >Créez la page de votre collectivité</router-link>
          </div>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script>
import { getCollectivity } from "@/api/collectivity";
import {
  AisInstantSearch,
  AisSearchBox,
  AisHits,
  AisPagination,
  AisStateResults,
  AisMenuSelect,
  AisClearRefinements,
  AisConfigure
} from "vue-instantsearch";
import algoliasearch from "algoliasearch/lite";
import "instantsearch.css/themes/algolia-min.css";

export default {
  name: "FrontCollectivity",
  components: {
    AisInstantSearch,
    AisSearchBox,
    AisHits,
    AisPagination,
    AisStateResults,
    AisMenuSelect,
    AisClearRefinements,
    AisConfigure
  },
  props: {
    slug: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      loading: false,
      collectivity: {},
      missionsAreReady: true,
      searchClient: algoliasearch(
        process.env.MIX_ALGOLIA_APP_ID,
        process.env.MIX_ALGOLIA_SEARCH_KEY
      ),
      filters: {
        department_name: null,
        domaine_action: null
      }
    };
  },
  computed: {
    modeLigth() {
      return process.env.MIX_MODE_APP_LIGTH
        ? JSON.parse(process.env.MIX_MODE_APP_LIGTH)
        : false;
    },
    indexName() {
      return process.env.MIX_ALGOLIA_INDEX;
    }
  },
  created() {
    this.$store.commit("setLoading", true);
    getCollectivity(this.slug)
      .then(response => {
        this.collectivity = { ...response.data };
        this.$store.commit("setLoading", false);
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {
    handleResetFilters(refine) {
      refine();
      this.filters.department_name = null;
      this.filters.domaine_action = null;
    },
    formatNbResults(nbHits, page, nbPages, hitsPerPage) {
      let begin = page * hitsPerPage + 1;
      let end =
        nbHits < (page + 1) * hitsPerPage ? nbHits : (page + 1) * hitsPerPage;
      return `<span class="font-medium">${begin}</span> à <span class="font-medium">${end}</span> sur <span class="font-medium">${nbHits}</span>`;
    },
    scrollToTop() {
      this.$refs.resultsWrapper.scrollIntoView();
    },
    transformItems(items) {
      return items.map(item => ({
        ...item,
        label: item.label
      }));
    },
    iconDomain(domain) {
      switch (domain) {
        case "Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis":
          return "/images/groceries.svg";
        case "Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance":
          return "/images/teddy-bear.svg";
        case "Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)":
          return "/images/phone-handle.svg";
        case "Je fais les courses de produits essentiels pour mes voisins les plus fragiles.":
          return "/images/basket.svg";
      }
    }
  }
};
</script>

<style lang="sass" scoped>
::v-deep .ais-SearchBox-form
  @apply m-0
::v-deep .ais-SearchBox-submit
  left: 15px
::v-deep .ais-StateResults
  @apply m-0 mb-16 bg-white rounded-lg shadow overflow-hidden
::v-deep .ais-Hits-list
  @apply m-0
::v-deep .ais-Hits-item
  @apply border-0 shadow-none w-full p-0 m-0 border-b border-gray-200
::v-deep .ais-Pagination-item
  @apply m-0
  &:not(.ais-Pagination-item--previousPage):not(.ais-Pagination-item--nextPage)
    @apply hidden
    @screen sm
      display: list-item
  &.ais-Pagination-item--previousPage
    @apply mr-auto
    .ais-Pagination-link
      @apply pl-0
  &.ais-Pagination-item--nextPage
    @apply ml-auto
    .ais-Pagination-link
      @apply pr-0
  &.ais-Pagination-item--selected
    .ais-Pagination-link
      @apply border-blue-600 text-blue-600
  .ais-Pagination-link
    @apply relative inline-flex items-center p-4 rounded-none border-0 border-t-2 border-transparent text-sm font-medium text-gray-700 bg-white transition ease-in-out duration-150
    &:hover
      @apply border-gray-300
.filters
  ::v-deep input, ::v-deep select
    font-size: 16px !important
    text-overflow: ellipsis
    @apply py-2 shadow rounded-lg my-1

  @screen md
    ::v-deep input, ::v-deep select, ::v-deep .el-input__inner
      height: 56px
      @apply border-0 rounded-none border-r border-dashed my-0 shadow-none bg-white
  ::v-deep .ais-SearchBox-input
    @apply rounded-l-lg outline-none pl-12
</style>
