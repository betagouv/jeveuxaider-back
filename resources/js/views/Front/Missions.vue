<template>
  <div class="bg-gray-100">
    <AppHeader />

    <template v-if="modeLigth">
      <div class="bg-blue-900 pb-32">
        <div class="container mx-auto px-4">
          <div class="pt-10">
            <h1 class="text-3xl font-bold text-white">Missions disponibles</h1>
          </div>
        </div>
      </div>
      <div class="-mt-32">
        <div class="container mx-auto px-4 my-12">
          <div
            class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
          >Les organisations en première ligne face à la crise proposent actuellement leurs missions prioritaires.<br> Elles seront mises en ligne très prochainement.<br> Revenez demain pour les découvrir !
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <ais-instant-search :search-client="searchClient" :index-name="indexName">
        <div class="bg-blue-900 pb-32">
          <div class="container mx-auto px-4">
            <div
              :class="[
              { 'py-10': missionsAreReady },
              { 'pt-10': !missionsAreReady }
            ]"
            >
              <h1 class="text-3xl font-bold text-white">Missions disponibles</h1>
            </div>
            <div class="filters md:flex md:rounded-lg md:shadow md:bg-white" v-if="missionsAreReady" >
                <ais-search-box class="flex-1" autofocus placeholder="Mots-clés, ville, code postal, etc." />
                <ais-menu-select class="flex-1" attribute="department_name" :limit="120">
                    <el-select
                        v-model="filters.department_name"
                        slot-scope="{ items, canRefine, refine }"
                        :disabled="!canRefine"
                        @change="refine($event)"
                        placeholder="Départements"
                    >
                        <el-option
                        v-for="item in items"
                        :key="item.value"
                        :label="item.label"
                        :selected="item.isRefined"
                        :value="item.value">
                        </el-option>
                    </el-select>
                </ais-menu-select>
                <ais-menu-select class="flex-1" attribute="domaine_action" :transform-items="transformItems">
                    <el-select
                        v-model="filters.domaine_action"
                        slot-scope="{ items, canRefine, refine }"
                        :disabled="!canRefine"
                        @change="refine($event)"
                        placeholder="Domaines d'actions"
                    >
                        <el-option
                        v-for="item in items"
                        :key="item.value"
                        :label="item.label"
                        :selected="item.isRefined"
                        :value="item.value">
                        </el-option>
                    </el-select>
                </ais-menu-select>
                <ais-clear-refinements>
                    <div
                        @click.prevent="handleResetFilters(refine)"
                        slot-scope="{ canRefine, refine }"
                        class="md:h-full py-2 md:p-4"
                        :class="{
                            'cursor-not-allowed text-gray-400 hidden md:block': !canRefine,
                            'cursor-pointer text-blue-300  md:text-primary block': canRefine,
                        }"
                    >
                        Réinitialiser
                    </div>
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
                        <div class="flex items-center px-4 py-4 sm:px-6">
                          <div class="min-w-0 flex-1 flex items-start">
                            <div class="hidden sm:block flex-shrink-0" style="margin-top:2px;">
                              <img
                                v-if="item.structure.logo"
                                class="h-12 w-12 rounded-full"
                                :src="item.structure.logo"
                                :alt="item.structure.name"
                              />
                              <div
                                v-else
                                class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center"
                              >{{ item.structure.name[0] }}</div>
                            </div>
                            <div class="min-w-0 flex-1 sm:px-4 md:grid md:grid-cols-2 md:gap-4">
                              <div class="col-span-2 lg:col-span-1 mb-4 md:mb-0">
                                <div class="font-semibold text-blue-800 truncate">{{ item.name }}</div>
                                <div
                                  class="mt-1 flex items-center text-sm ext-gray-900 font-semibold"
                                >
                                  <span class="truncate">{{ item.structure.name }}</span>
                                </div>
                              </div>

                              <div
                                class="flex flex-wrap items-center -mx-2 -my-1 col-span-2 lg:col-span-1 text-sm"
                              >
                                <div
                                  class="mx-2 my-1 flex items-center text-s leading-5 text-gray-500"
                                >
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
                                  {{ item.city }} ({{ item.department }})
                                </div>
                                <!-- <div
                                v-if="item.periodicite"
                                class="mx-2 my-1 flex items-center text-gray-500"
                              >
                                <svg
                                  class="flex-shrink-0 mr-2 h-5 w-5 text-gray-400"
                                  fill="currentColor"
                                  viewBox="0 0 20 20"
                                >
                                  <path
                                    fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd"
                                  ></path>
                                </svg>
                                {{ item.periodicite }}
                                </div>-->

                                <span
                                  class="mx-2 my-1 px-6 py-1 shadow-md inline-flex font-semibold text-center rounded-full bg-green-100 text-green-800"
                                >
                                  {{ item.participations_max }}
                                  {{
                                  item.participations_max
                                  | pluralize(["volontaire", "volontaires"])
                                  }}
                                  <!-- <div>
                                <div v-if="item.has_places_left">
                                  {{ item.places_left }}
                                  {{
                                  (item.places_left)
                                  | pluralize(["place restante", "places restantes"])
                                  }}
                                </div>
                                <div v-else>Complet</div>
                                <div
                                  class="font-light text-gray-600 text-xs"
                                >{{ item.participations_max - item.places_left }} / {{ item.participations_max }}
                                </div>
                                  </div>-->
                                </span>
                              </div>
                            </div>
                          </div>
                          <div>
                            <svg
                              class="h-5 w-5 text-gray-400"
                              fill="currentColor"
                              viewBox="0 0 20 20"
                            >
                              <path
                                fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"
                              />
                            </svg>
                          </div>
                        </div>
                      </router-link>
                    </div>
                  </ais-hits>

                  <div class="flex items-center px-4 py-4 sm:px-6">
                    <!-- <div class="text-sm text-gray-700">
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
                    </div> -->
                    <div class="pagination ml-auto">
                      <ais-pagination @page-change="scrollToTop" />
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
    <AppFooter />
  </div>
</template>

<script>
import {
  AisInstantSearch,
  AisSearchBox,
  AisHits,
  AisPagination,
  AisStateResults,
  AisMenuSelect,
  AisClearRefinements
} from "vue-instantsearch";
import algoliasearch from "algoliasearch/lite";
import "instantsearch.css/themes/algolia-min.css";

export default {
  name: "FrontMissions",
  components: {
    AisInstantSearch,
    AisSearchBox,
    AisHits,
    AisPagination,
    AisStateResults,
    AisMenuSelect,
    AisClearRefinements
  },
  data() {
    return {
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
      return process.env.MIX_MODE_APP_LIGTH ? JSON.parse(process.env.MIX_MODE_APP_LIGTH) : false;
    },
    indexName() {
      return process.env.MIX_ALGOLIA_INDEX;
    }
  },
  methods: {
    handleResetFilters(refine) {
          refine()
          this.filters.department_name = null
          this.filters.domaine_action = null
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
          label: (item.label.length > 60) ? item.label.substring(0, 60) + "..." : item.label,
        }));
      },
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
    &:not(.ais-Pagination-item--previousPage):not(.ais-Pagination-item--nextPage)
        @apply hidden
    .ais-Pagination-link
        @apply relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white transition ease-in-out duration-150
        &:hover
            @pply text-gray-500
        &:focus
            @apply outline-none border-blue-300
        &:active
            @apply bg-gray-100 text-gray-700
.filters
    ::v-deep input, ::v-deep select
        @apply py-2 shadow rounded-lg my-1
        font-size: 16px !important
    @screen md
        ::v-deep input, ::v-deep select, ::v-deep .el-input__inner
                @apply h-full border-0 rounded-none border-r my-0 shadow-none bg-white
    ::v-deep .ais-SearchBox-input
        @apply rounded-l-lg outline-none px-12
    @screen md
        ::v-deep .is-disabled
            @apply h-full
    @screen md
        ::v-deep .el-input__inner
            height: 100% !important
</style>
