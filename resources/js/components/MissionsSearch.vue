<template>
  <div>
    <div class="">
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
              :filters.camel="queryFilters"
            />

            <div
              class="filters mb-3 md:flex md:rounded-lg md:shadow md:bg-white"
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
                v-if="activeFilters.includes('department_name')"
                class="flex-1"
                attribute="department_name"
                :transform-items="transformItems"
                :limit="120"
              >
                <el-select
                  v-model="filters.department_name"
                  slot-scope="{ items, canRefine, refine }"
                  :disabled="!canRefine"
                  placeholder="Départements"
                  popper-class="departments"
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
                v-if="activeFilters.includes('domaines')"
                class="flex-1"
                attribute="domaines"
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
                v-if="activeFilters.includes('template_title')"
                class="flex-1"
                attribute="template_title"
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
                                  :class="`bg-${color}`"
                                  class="hidden sm:block flex-shrink-0 rounded-md p-3 text-center"
                                >
                                  <img
                                    class
                                    :src="item.domaine_image"
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
                                        v-text="item.domaine_name"
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
                                      :class="`bg-${color}`"
                                      class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
                                    >
                                      <template>
                                        {{ item.places_left | formatNumber }}
                                        {{
                                          item.places_left
                                            | pluralize([
                                              'bénévole recherché',
                                              'bénévoles recherchés',
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
                        <!-- <div
                          class="text-sm font-bold uppercase my-8 text-blue text-blue-600 text-center"
                        >
                          <router-link
                            :to="`/missions?menu%5Bdepartment_name%5D=${$options.filters.fullDepartmentFromValue(
                              department
                            )}`"
                          >
                            Toutes les missions
                          </router-link>
                        </div> -->
                        <div
                          class="pagination w-full border-b-2 border-transparent"
                        >
                          <ais-pagination
                            :padding="2"
                            @page-change="scrollToTop"
                          >
                            <ul
                              slot-scope="{
                                currentRefinement,
                                nbPages,
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
                                  {
                                    'ais-Pagination-item--disabled': isFirstPage,
                                  },
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
                                v-for="page in pages"
                                :key="page"
                                class="ais-Pagination-item"
                                :class="[
                                  {
                                    'ais-Pagination-item--selected':
                                      currentRefinement === page,
                                  },
                                ]"
                              >
                                <a
                                  :href="createURL(page)"
                                  class="ais-Pagination-link"
                                  @click.prevent="
                                    currentRefinement !== page
                                      ? refine(page)
                                      : null
                                  "
                                  >{{ page + 1 }}</a
                                >
                              </li>
                              <li
                                class="ais-Pagination-item ais-Pagination-item--nextPage"
                                :class="[
                                  {
                                    'ais-Pagination-item--disabled': isLastPage,
                                  },
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
  AisPagination,
  AisSearchBox,
  AisMenuSelect,
  AisClearRefinements,
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
    AisPagination,
    AisSearchBox,
    AisMenuSelect,
    AisClearRefinements,
  },
  props: {
    activeFilters: {
      type: Array,
      default() {
        return ['domaines', 'template_title']
      },
    },
    facetFilters: {
      type: Array,
      default: null,
    },
    queryFilters: {
      type: String,
      default: '',
    },
    color: {
      type: String,
      default: 'blue-800',
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
        domaines: null,
        template_title: null,
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
