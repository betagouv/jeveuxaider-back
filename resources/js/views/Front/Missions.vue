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
          >
            Les organisations en première ligne face à la crise proposent
            actuellement leurs missions prioritaires.<br />
            Elles seront mises en ligne très prochainement.<br />
            Revenez demain pour les découvrir !
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <ais-instant-search ref="instantsearch" :search-client="searchClient" :index-name="indexName" :routing="routing">
        <ais-configure :hits-per-page.camel="20" />

        <div class="bg-blue-900 pb-32">
          <div class="container mx-auto px-4">
            <div
              :class="[
                { 'py-10': missionsAreReady },
                { 'pt-10': !missionsAreReady }
              ]"
            >
              <h1 class="text-3xl font-bold text-white">
                Missions disponibles
              </h1>
            </div>
            <div
              class="filters md:flex md:rounded-lg md:shadow md:bg-white"
              v-if="missionsAreReady"
            >
              <ais-search-box
                class="flex-1"
                autofocus
                placeholder="Mots-clés, ville, code postal, etc."
                ref="searchbox"
              >
              </ais-search-box>
              <ais-menu-select
                class="flex-1"
                attribute="department_name"
                :limit="120"
              >
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
                    :label="`${item.label} (${item.count})`"
                    :value="item.value"
                  >
                  </el-option>
                </el-select>
              </ais-menu-select>
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
                  >
                  </el-option>
                </el-select>
              </ais-menu-select>
              <ais-clear-refinements>
                <div
                  @click.prevent="handleResetFilters(refine)"
                  slot-scope="{ canRefine, refine }"
                  class="py-2 md:p-4"
                  :class="{
                    'cursor-not-allowed text-gray-400 hidden md:block': !canRefine && !$refs.searchbox.state.query,
                    'cursor-pointer text-blue-300  md:text-primary block': canRefine || $refs.searchbox.state.query
                  }"
                >
                  Réinitialiser
                </div>
              </ais-clear-refinements>
            </div>
          </div>
        </div>

        <div ref="resultsWrapper" class="-mt-32">
          <div class="container mx-auto px-4 mt-4">
            <div
              v-if="!missionsAreReady"
              class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16 mb-16"
            >
              Missions en cours de validation...
            </div>

            <ais-state-results v-else>
              <template
                slot-scope="{ hits, nbHits, page, nbPages, hitsPerPage }"
              >
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
                                class=""
                                :src="$options.filters.domainIcon(item.domaine_action)"
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
                                  >{{ item.name|labelFromValue('mission_domaines') }}</div>
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
                                <div v-else class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
                                  style="background:#d2d6dc;">Complet</div>
                              </div>
                            </div>
                          </div>

                          <div
                            class="mt-4 flex items-start text-sm text-gray-500"
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
                            <span
                              v-text="
                                `${item.city} (${item.department}) - ${item.structure.name}`
                              "
                            >
                            </span>
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
                    <div
                      class="pagination w-full border-b-2 border-transparent"
                    >
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
                                ></path>
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
                            >
                              {{ page + 1 }}
                            </a>
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
                                ></path>
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
                >
                  Pas de résultats.
                </div>
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
  AisClearRefinements,
  AisConfigure
} from "vue-instantsearch";
import algoliasearch from "algoliasearch/lite";
import "instantsearch.css/themes/algolia-min.css";
import { history as historyRouter } from 'instantsearch.js/es/lib/routers';
import { simple as simpleMapping } from 'instantsearch.js/es/lib/stateMappings';

export default {
  name: "FrontMissions",
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
  beforeRouteUpdate(to, from, next) {
    // Do nothing, navigation is handled by algolia
    // If trying to use vue routeur instead of algolia, remove
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
      },
      routing: {
        router: historyRouter({
          createURL: ({ qsModule, location, routeState }) => {
            if (location.pathname != '/missions') {
              return;
            }
            console.log(location)
            const { origin, pathname, hash } = location;
            const queryString = qsModule.stringify(routeState);

            if (routeState.menu) {
              this.filters.department_name = routeState.menu.department_name ? routeState.menu.department_name : null
              this.filters.domaine_action = routeState.menu.domaine_action ? routeState.menu.domaine_action : null
            }
            else {
              this.filters.department_name = null
              this.filters.domaine_action = null
            }
            if (!queryString) {
              return `${origin}${pathname}${hash}`;
            }
            return `${origin}${pathname}?${queryString}${hash}`;
          }
        }),
        stateMapping: simpleMapping()
      },
      //
      // WITH VUEJS ROUTEUR
      // See: https://www.algolia.com/doc/guides/building-search-ui/going-further/routing-urls/vue/#combining-with-vue-router
      // And: https://github.com/algolia/vue-instantsearch/issues/589
      // Exemple when it works: https://codesandbox.io/s/github/algolia/doc-code-samples/tree/master/Vue+InstantSearch/routing-vue-router?file=/src/views/Home.vue:2558-3190
      //

      // routing: {
      //   router: {
      //     read: () => {
      //       console.log('read', this.$router.currentRoute.query)

      //       if (this.$router.currentRoute.query.menu) {
      //         this.filters.department_name = this.$router.currentRoute.query.menu.department_name ? this.$router.currentRoute.query.menu.department_name : null
      //       }
      //       else {
      //         this.filters.department_name = null
      //       }

      //       return this.$router.currentRoute.query;
      //     },
      //     write: (routeState) => {
      //       console.log('write', routeState)

      //       this.$router.push({
      //         query: routeState,
      //       });
      //     },
      //     createURL: (routeState) => {
      //       console.log('createUrl', routeState)

      //       return this.$router.resolve({
      //         query: routeState,
      //       }).href;
      //     },
      //     onUpdate(cb) {
      //       this._onPopState = ({state}) => {
      //         console.log('onUpdate')
      //         const routeState = state;
      //         // at initial load, the state is read from the URL without
      //         // update. Therefore the state object is not there. In this
      //         // case we fallback and read the URL.
      //         if (!routeState) {
      //           cb(this.read());
      //         } else {
      //           cb(routeState);
      //         }
      //       };
      //       window.addEventListener('popstate', this._onPopState);
      //     },
      //     dispose() {
      //       console.log('dispose')
      //       window.removeEventListener('popstate', this._onPopState);
      //       this.write();
      //     },
      //   },
      //   stateMapping: {
      //     stateToRoute(uiState) {
      //       return uiState
      //     },
      //     routeToState(routeState) {
      //       return routeState
      //     },
      //   },
      // }
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
  methods: {
    handleResetFilters(refine) {
      this.$refs.searchbox.state.clear()
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
