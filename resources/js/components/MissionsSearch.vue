<template>
  <div>
    <div class="bg-blue-900">
      <div class="pt-16 pb-8">
        <template v-if="modeLigth">
          <div class="">
            <div class="px-4 my-12">
              <div
                class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
              >
                Les organisations en première ligne face à la crise proposent
                actuellement leurs missions prioritaires.
                <br />Elles seront mises en ligne très prochainement.
                <br />Revenez demain pour les découvrir !
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <ais-instant-search
            :search-client="searchClient"
            :index-name="indexName"
          >
            <ais-configure
              :hits-per-page.camel="10"
              :facet-filters.camel="facetFilters"
            />

            <div ref="resultsWrapper" class="">
              <div class="">
                <ais-state-results>
                  <template slot-scope="{ hits }">
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
                                    :src="
                                      $options.filters.domainIcon(
                                        item.domaine_action
                                      )
                                    "
                                    style="width: 28px;"
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
                                      />
                                      <div
                                        class="text-sm md:text-base lg:text-lg xl:text-xl font-semibold text-gray-900 truncate"
                                      >
                                        {{ item.name }}
                                      </div>
                                    </div>

                                    <div
                                      v-if="
                                        item.has_places_left &&
                                        item.places_left > 0
                                      "
                                      class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
                                      style="background: #31c48d;"
                                    >
                                      <template>
                                        {{ item.places_left | formatNumber }}
                                        {{
                                          item.places_left
                                            | pluralize([
                                              'volontaire recherché',
                                              'volontaires recherchés',
                                            ])
                                        }}
                                      </template>
                                    </div>
                                    <div
                                      v-else
                                      class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
                                      style="background: #d2d6dc;"
                                    >
                                      Complet
                                    </div>
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
                                />
                              </div>
                            </div>
                          </router-link>
                        </div>
                      </ais-hits>

                      <div class="px-4 sm:px-6 md:px-8">
                        <div
                          class="text-sm font-bold uppercase my-8 text-blue text-blue-600 text-center"
                        >
                          <router-link
                            :to="`/missions?menu%5Bdepartment_name%5D=${$options.filters.fullDepartmentFromValue(
                              department
                            )}`"
                          >
                            Toutes les missions
                          </router-link>
                        </div>
                        <!-- <div class="pagination w-full border-b-2 border-transparent">
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
                        </div> -->
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
      </div>
    </div>
  </div>
</template>

<script>
import {
  AisInstantSearch,
  AisHits,
  AisStateResults,
  AisConfigure,
} from 'vue-instantsearch'
import algoliasearch from 'algoliasearch/lite'
import 'instantsearch.css/themes/algolia-min.css'

export default {
  name: 'MissionsSearch',
  components: {
    AisInstantSearch,
    AisHits,
    AisStateResults,
    AisConfigure,
  },
  props: {
    facetFilters: {
      type: Array,
      default: null,
    },
    department: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      searchClient: algoliasearch(
        process.env.MIX_ALGOLIA_APP_ID,
        process.env.MIX_ALGOLIA_SEARCH_KEY
      ),
      filters: {
        department_name: null,
        domaine_action: null,
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
  created() {
    // this.searchClient.searchForFacetValues('department_name', '64 - Pyrénnées-Atlantiques')
  },
  methods: {
    handleResetFilters(refine) {
      refine()
      this.filters.department_name = null
      this.filters.domaine_action = null
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
  },
}
</script>

<style lang="sass" scoped>
::v-deep .ais-SearchBox-form
  @apply m-0
::v-deep .ais-SearchBox-submit
  left: 15px
::v-deep .ais-StateResults
  @apply m-0 bg-white rounded-lg shadow overflow-hidden
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
