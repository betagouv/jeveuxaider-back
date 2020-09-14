<template>
  <div class="bg-gray-100">
    <AppHeader />

    <template v-if="modeLigth">
      <div class="bg-primary pb-32">
        <div class="container mx-auto px-4">
          <div class="pt-10">
            <h1 class="text-3xl font-bold text-white">
              Missions disponibles
            </h1>
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
      <ais-instant-search
        ref="instantsearch"
        :search-client="searchClient"
        :index-name="indexName"
        :routing="routing"
      >
        <ais-configure :hits-per-page.camel="20" />

        <div class="bg-primary pb-32">
          <div class="container mx-auto px-4">
            <div
              :class="[
                { 'py-10': missionsAreReady },
                { 'pt-10': !missionsAreReady },
              ]"
            >
              <h1 class="text-3xl font-bold text-white">
                Missions disponibles
              </h1>
            </div>
            <div
              v-if="missionsAreReady"
              class="filters md:flex md:rounded-lg md:shadow md:bg-white"
            >
              <ais-search-box
                ref="searchbox"
                class="flex-1"
                autofocus
                placeholder="Mots-clés, ville, code postal, etc."
              >
                <div
                  slot-scope="{ currentRefinement, isSearchStalled, refine }"
                >
                  <el-input
                    v-model="filters.query"
                    placeholder="Mots-clés, ville, code postal, etc."
                    clearable
                    class="search-input"
                    autocomplete="new-password"
                    @input="handleFilters(refine, $event)"
                  >
                    <svg
                      slot="prefix"
                      role="img"
                      xmlns="http://www.w3.org/2000/svg"
                      width="10"
                      height="10"
                      viewBox="0 0 40 40"
                      class="el-input__icon"
                      style="width: 14px;"
                    >
                      <path
                        d="M26.804 29.01c-2.832 2.34-6.465 3.746-10.426 3.746C7.333 32.756 0 25.424 0 16.378 0 7.333 7.333 0 16.378 0c9.046 0 16.378 7.333 16.378 16.378 0 3.96-1.406 7.594-3.746 10.426l10.534 10.534c.607.607.61 1.59-.004 2.202-.61.61-1.597.61-2.202.004L26.804 29.01zm-10.426.627c7.323 0 13.26-5.936 13.26-13.26 0-7.32-5.937-13.257-13.26-13.257C9.056 3.12 3.12 9.056 3.12 16.378c0 7.323 5.936 13.26 13.258 13.26z"
                        fillRule="evenodd"
                        fill="#6a6f85"
                      />
                    </svg>
                  </el-input>
                </div>
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
                  placeholder="Départements"
                  @change="handleFilters(refine, $event)"
                >
                  <el-option
                    v-for="item in items"
                    :key="item.value"
                    :label="`${item.label} (${item.count})`"
                    :value="item.value"
                  />
                </el-select>
              </ais-menu-select>
              <ais-menu-select
                class="flex-1"
                attribute="domaines"
                :limit="100"
                :transform-items="transformItems"
              >
                <el-select
                  v-model="filters.domaines"
                  slot-scope="{ items, canRefine, refine }"
                  :disabled="!canRefine"
                  placeholder="Domaines d'action"
                  popper-class="domaines-actions"
                  @change="handleFilters(refine, $event)"
                >
                  <el-option
                    v-for="item in items"
                    :key="item.value"
                    :label="`${item.label} (${item.count})`"
                    :selected="item.isRefined"
                    :value="item.value"
                  />
                </el-select>
              </ais-menu-select>
              <ais-menu-select
                class="flex-1"
                attribute="template_title"
                :limit="100"
                :transform-items="transformItems"
              >
                <el-select
                  v-model="filters.template_title"
                  slot-scope="{ items, canRefine, refine }"
                  :disabled="!canRefine"
                  placeholder="Missions types"
                  popper-class="missions-types"
                  @change="handleFilters(refine, $event)"
                >
                  <el-option
                    v-for="item in items"
                    :key="item.value"
                    :label="`${item.label} (${item.count})`"
                    :selected="item.isRefined"
                    :value="item.value"
                  />
                </el-select>
              </ais-menu-select>

              <ais-clear-refinements>
                <div
                  slot-scope="{ canRefine, refine }"
                  class="py-2 md:p-4"
                  :class="{
                    'cursor-not-allowed text-gray-400 hidden md:block':
                      !canRefine && !$refs.searchbox.state.query,
                    'cursor-pointer text-blue-300  md:text-primary block':
                      canRefine || $refs.searchbox.state.query,
                  }"
                  @click.prevent="handleResetFilters(refine)"
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
                      <a
                        v-if="item.provider == 'api_engagement'"
                        class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out"
                        :href="item.application_url"
                        target="_blank"
                      >
                        <MissionSearch :mission="item" />
                      </a>
                      <router-link
                        v-else
                        class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out"
                        :to="`/missions/${item.id}`"
                      >
                        <MissionSearch :mission="item" />
                      </router-link>
                    </div>
                  </ais-hits>

                  <div class="px-4 sm:px-6 md:px-8">
                    <div v-show="false" class="text-sm text-gray-700">
                      <span
                        v-html="
                          formatNbResults(nbHits, page, nbPages, hitsPerPage)
                        "
                      />
                      {{
                        nbHits
                          | pluralize([
                            'mission disponible',
                            'missions disponibles',
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
                            pages,
                            isFirstPage,
                            isLastPage,
                            refine,
                            createURL,
                          }"
                          class="ais-Pagination-list"
                        >
                          <li
                            class="ais-Pagination-item ais-Pagination-item--previousPage"
                            :class="[
                              { 'ais-Pagination-item--disabled': isFirstPage },
                            ]"
                          >
                            <a
                              :href="createURL(currentRefinement - 1)"
                              class="ais-Pagination-link"
                              @click.prevent="
                                !isFirstPage
                                  ? refine(currentRefinement - 1)
                                  : null
                              "
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
                            v-for="pageItem in pages"
                            :key="pageItem"
                            class="ais-Pagination-item"
                            :class="[
                              {
                                'ais-Pagination-item--selected':
                                  currentRefinement === pageItem,
                              },
                            ]"
                          >
                            <a
                              :href="createURL(pageItem)"
                              class="ais-Pagination-link"
                              @click.prevent="
                                currentRefinement !== pageItem
                                  ? refine(pageItem)
                                  : null
                              "
                            >
                              {{ pageItem + 1 }}
                            </a>
                          </li>
                          <li
                            class="ais-Pagination-item ais-Pagination-item--nextPage"
                            :class="[
                              { 'ais-Pagination-item--disabled': isLastPage },
                            ]"
                          >
                            <a
                              :href="createURL(currentRefinement + 1)"
                              class="ais-Pagination-link"
                              @click.prevent="
                                !isLastPage
                                  ? refine(currentRefinement + 1)
                                  : null
                              "
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
  AisConfigure,
} from 'vue-instantsearch'
import algoliasearch from 'algoliasearch/lite'
import 'instantsearch.css/themes/algolia-min.css'
import { simple as simpleMapping } from 'instantsearch.js/es/lib/stateMappings'
import MissionSearch from '@/components/MissionSearch'
import _ from 'lodash'
import qs from 'qs'

export default {
  name: 'FrontMissions',
  components: {
    AisInstantSearch,
    AisSearchBox,
    AisHits,
    AisPagination,
    AisStateResults,
    AisMenuSelect,
    AisClearRefinements,
    AisConfigure,
    MissionSearch,
  },
  data() {
    return {
      missionsAreReady: true,
      searchClient: algoliasearch(
        process.env.MIX_ALGOLIA_APP_ID,
        process.env.MIX_ALGOLIA_SEARCH_KEY
      ),
      filters: {
        query: null,
        department_name: null,
        domaines: null,
        template_title: null,
      },
      forceWrite: false,
      routing: {
        router: {
          read: () => {
            let query = this.parseQuery(this.$router.currentRoute.query)
            this.synchronizeFilters(query)
            return query
          },
          write: (routeState) => {
            if (this.writeTimeout) {
              this.writeTimeout.cancel()
            }
            if (this.forceWrite) {
              this.writeTimeout = _.debounce(() => {
                window.history.pushState(
                  routeState,
                  '',
                  `${this.$router.currentRoute.path}${this.stringifyQuery(
                    routeState
                  )}`
                )
                this.forceWrite = false
              }, 400)
              this.writeTimeout()
            }
          },
          createURL: (routeState) => {
            return this.$router.resolve({
              query: routeState,
            }).href
          },
          onUpdate: (cb) => {
            if (this.writeTimeout) {
              this.writeTimeout.cancel()
            }

            this._onPopState = () => {
              cb(this.routing.router.read())
            }
            window.addEventListener('popstate', this._onPopState)
          },
          dispose: () => {
            window.removeEventListener('popstate', this._onPopState)
          },
        },
        stateMapping: simpleMapping(),
      },
    }
  },
  computed: {
    modeLigth() {
      return process.env.MIX_MODE_APP_LIGTH
        ? JSON.parse(process.env.MIX_MODE_APP_LIGTH)
        : false
    },
    indexName() {
      return process.env.MIX_ALGOLIA_INDEX
    },
  },
  methods: {
    synchronizeFilters(state) {
      this.filters.query = state.query ? state.query : null
      if (state.menu) {
        this.filters.department_name = state.menu.department_name
          ? state.menu.department_name
          : null
        this.filters.domaines = state.menu.domaines ? state.menu.domaines : null
      } else {
        this.filters.department_name = null
        this.filters.domaines = null
      }
    },
    handleFilters(refine, $event) {
      this.forceWrite = true
      refine($event)
    },
    handleResetFilters(refine) {
      this.forceWrite = true
      this.$refs.searchbox.state.clear()
      refine()
      this.filters.query = null
      this.filters.department_name = null
      this.filters.domaines = null
      this.filters.template_title = null
    },
    formatNbResults(nbHits, page, nbPages, hitsPerPage) {
      let begin = page * hitsPerPage + 1
      let end =
        nbHits < (page + 1) * hitsPerPage ? nbHits : (page + 1) * hitsPerPage
      return `<span class="font-medium">${begin}</span> à <span class="font-medium">${end}</span> sur <span class="font-medium">${nbHits}</span>`
    },
    scrollToTop() {
      this.$refs.resultsWrapper.scrollIntoView()
    },
    transformItems(items) {
      return items.map((item) => ({
        ...item,
        label: item.label,
      }))
    },
    parseQuery(query) {
      return qs.parse(query)
    },
    stringifyQuery(query) {
      const result = qs.stringify(query)
      return result ? '?' + result : ''
    },
  },
}
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
  ::v-deep .search-input
    .el-input__inner
      @apply rounded-l-lg outline-none pl-12
  ::v-deep .el-input__prefix
    left: 15px
  @screen md
    ::v-deep input,
    ::v-deep select,
    ::v-deep .el-select .el-input__inner
      height: 56px
      @apply border-0 rounded-none border-r border-dashed my-0 shadow-none bg-white
</style>
