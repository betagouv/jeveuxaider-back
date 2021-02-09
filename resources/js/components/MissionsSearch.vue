<template>
  <div class="bg-gray-100">
    <template v-if="modeLight">
      <div class="bg-primary pb-32">
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
      <transition name="fade">
        <ais-instant-search
          v-show="!loading"
          ref="instantsearch"
          :search-client="searchClient"
          :index-name="indexName"
          :initial-ui-state="routeStateWithIndex"
        >
          <ais-configure
            ref="aisConfigure"
            :hits-per-page.camel="18"
            :around-lat-lng.camel="aroundLatLng"
            :around-lat-lng-via-i-p.camel="
              aroundLatLng ||
              (routeState.refinementList &&
                routeState.refinementList.type &&
                routeState.refinementList.type[0] == 'Mission à distance')
                ? false
                : true
            "
            :around-radius.camel="aroundRadius"
            :get-ranking-info.camel="true"
            :filters.camel="aisFilters"
          />

          <ais-state-results>
            <template slot-scope="{ hits, nbHits }">
              <!-- Header -->
              <div
                class="header pt-4 lg:pt-7 pb-8 text-white"
                :class="[
                  `bg-${color}`,
                  { 'custom-color': $options.propsData.color },
                ]"
              >
                <div class="container mx-auto">
                  <div class="px-4">
                    <div
                      class="flex flex-wrap justify-between items-center -m-2"
                    >
                      <div class="m-2">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-black">
                          Trouver une mission de bénévolat
                        </h1>
                        <div>
                          {{ nbHits | formatNumber }}
                          {{
                            nbHits
                              | pluralize([
                                'mission de bénévolat disponible',
                                'missions de bénévolat disponibles',
                              ])
                          }}
                        </div>
                      </div>

                      <div class="w-full lg:w-auto m-2">
                        <div class="flex flex-col lg:flex-row items-center">
                          <AlgoliaLieuSwitcher
                            class="w-full my-4 lg:my-0"
                            :initial-type="type"
                            :initial-place="placeLabel"
                            :around-radius="aroundRadius"
                            :color="$options.propsData.color ? color : null"
                            @selected="onPlaceSelect($event)"
                            @clear="onPlaceClear"
                            @typeChanged="onTypeChanged($event)"
                            @typeRemoved="onTypeRemoved()"
                            @change-radius="onChangeRadius($event)"
                          />

                          <div
                            class="toggle-filters w-full p-2 pr-3 lg:hidden border border-white rounded-lg flex items-center justify-center"
                            @click="showFilters = !showFilters"
                          >
                            <img
                              class="flex-none mr-4"
                              src="/images/filter.svg"
                              alt="Filtrer"
                            />
                            <span>Précisez votre recherche</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Content -->
              <div class="container mx-auto">
                <div ref="contentWrapper" class="px-4 pt-8">
                  <div class="flex">
                    <!-- Filtres -->
                    <transition name="fade">
                      <div
                        v-show="showFilters"
                        class="facets--wrapper flex-none mb-8 w-full lg:w-64 lg:mr-8"
                      >
                        <div class="border-b lg:hidden">
                          <div class="p-2 flex items-center justify-between">
                            <div class="p-2">
                              <span class="font-bold">
                                {{ nbHits | formatNumber }}
                              </span>
                              <span class="font-light">
                                {{
                                  nbHits
                                    | pluralize([
                                      'mission disponible',
                                      'missions disponibles',
                                    ])
                                }}
                              </span>
                            </div>

                            <div
                              class="p-2 right-0 top-0"
                              @click="showFilters = false"
                            >
                              <div
                                class="text-center px-4 py-2 rounded-full text-white shadow-md cursor-pointer bg-primary"
                              >
                                Afficher
                              </div>
                            </div>
                          </div>
                        </div>

                        <div
                          v-scroll-lock="showFilters && isMobile"
                          class="px-4 pt-8 pb-32 lg:p-0 overflow-y-auto lg:overflow-hidden flex flex-col flex-1"
                        >
                          <AisClearRefinements
                            :excluded-attributes="clearExcludes"
                          >
                            <div slot-scope="{ canRefine, refine }">
                              <div
                                v-if="canRefine"
                                class="clear-refinements"
                                @click.prevent="onResetFilters(refine)"
                              >
                                <span class="mr-auto">
                                  Effacer tous les filtres
                                </span>
                                <div
                                  class="ml-3 rounded-full bg-gray-100 w-6 h-6 relative flex items-center justify-center"
                                >
                                  <img
                                    class="clear-refinement--icon"
                                    src="/images/close.svg"
                                    width="8px"
                                    height="8px"
                                  />
                                </div>
                              </div>
                            </div>
                          </AisClearRefinements>

                          <portal-target name="mobile" />

                          <div class="font-black text-gray-1000 mb-2 lg:hidden">
                            Mots clés
                          </div>

                          <AisSearchBox ref="searchbox" class="mb-8">
                            <div
                              slot-scope="{
                                currentRefinement,
                                isSearchStalled,
                                refine,
                              }"
                            >
                              <el-input
                                v-model="routeState.query"
                                label="Recherche"
                                placeholder="Recherche par mots-clés"
                                clearable
                                class="search-input"
                                autocomplete="new-password"
                                @input="onQueryInput(refine, $event)"
                                @clear="onQueryClear"
                              />
                            </div>
                          </AisSearchBox>

                          <AlgoliaSearchFacet
                            v-if="facets.includes('domaines')"
                            name="domaines"
                            label="Domaines d'action"
                            class="mb-6"
                            @toggle-facet="onToggleFacet($event)"
                          />

                          <AlgoliaSearchFacet
                            v-if="facets.includes('format')"
                            name="format"
                            label="Format de mission"
                            class="mb-6"
                            :sort-by="['count:desc']"
                            @toggle-facet="onToggleFacet($event)"
                          />

                          <AlgoliaSearchFacet
                            v-if="facets.includes('template_title')"
                            name="template_title"
                            label="Type de mission"
                            is-searchable
                            class="mb-6"
                            @toggle-facet="onToggleFacet($event)"
                          />

                          <AlgoliaSearchFacet
                            v-if="facets.includes('department_name')"
                            name="department_name"
                            label="Département"
                            is-searchable
                            class="mb-6"
                            :sort-by="['isRefined', 'name:asc']"
                            @toggle-facet="onToggleFacet($event)"
                          />

                          <AlgoliaSearchFacet
                            v-if="facets.includes('structure.name')"
                            name="structure.name"
                            label="Organisations"
                            is-searchable
                            class="mb-6"
                            @toggle-facet="onToggleFacet($event)"
                          />
                        </div>
                      </div>
                    </transition>

                    <!-- Résultats -->
                    <div v-if="hits.length > 0" class="w-full mb-16">
                      <ais-hits>
                        <div
                          slot="item"
                          slot-scope="{ item }"
                          class="flex flex-col flex-1"
                        >
                          <a
                            v-if="item.provider == 'api_engagement'"
                            class="flex flex-col flex-1 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out"
                            :href="item.application_url"
                            target="_blank"
                          >
                            <CardMission :mission="item" />
                          </a>
                          <router-link
                            v-else
                            class="flex flex-col flex-1 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out"
                            :to="`/missions/${item.id}/${item.slug}`"
                          >
                            <CardMission :mission="item" />
                          </router-link>
                        </div>
                      </ais-hits>

                      <ais-pagination class="mt-6" @page-change="scrollToTop">
                        <ul
                          slot-scope="{
                            currentRefinement,
                            pages,
                            isFirstPage,
                            isLastPage,
                            refine,
                          }"
                          class="flex lg:ml-3"
                        >
                          <li
                            class="mr-auto"
                            :class="[
                              { 'cursor-not-allowed': isFirstPage },
                              { 'cursor-pointer': !isFirstPage },
                            ]"
                            @click.prevent="
                              !isFirstPage
                                ? refine(currentRefinement - 1)
                                : null
                            "
                          >
                            <span>Précédent</span>
                          </li>

                          <li
                            v-for="pageItem in pages"
                            :key="pageItem"
                            class="page-number cursor-pointer"
                            :class="[
                              {
                                active: currentRefinement === pageItem,
                              },
                            ]"
                            @click.prevent="
                              currentRefinement !== pageItem
                                ? refine(pageItem)
                                : null
                            "
                          >
                            {{ pageItem + 1 }}
                          </li>
                          <li
                            class="ml-auto"
                            :class="[
                              { 'cursor-not-allowed': isLastPage },
                              { 'cursor-pointer': !isLastPage },
                            ]"
                            @click.prevent="
                              !isLastPage ? refine(currentRefinement + 1) : null
                            "
                          >
                            <span>Suivant</span>
                          </li>
                        </ul>
                      </ais-pagination>
                    </div>

                    <div
                      v-else
                      class="w-full h-full mb-16 bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
                    >
                      Pas de résultats.
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </ais-state-results>
        </ais-instant-search>
      </transition>
    </template>
  </div>
</template>

<script>
import {
  AisInstantSearch,
  AisStateResults,
  AisConfigure,
  AisHits,
  AisPagination,
  AisClearRefinements,
  AisSearchBox,
} from 'vue-instantsearch'

import AlgoliaSearchFacet from '@/components/AlgoliaSearchFacet'
import CardMission from '@/components/CardMission'
import AlgoliaSearch from '@/mixins/AlgoliaSearch'
import AlgoliaLieuSwitcher from '@/components/AlgoliaLieuSwitcher.vue'

export default {
  name: 'FrontMissions',
  components: {
    AisInstantSearch,
    AisStateResults,
    AisConfigure,
    AisHits,
    AisPagination,
    AlgoliaSearchFacet,
    CardMission,
    AlgoliaLieuSwitcher,
    AisClearRefinements,
    AisSearchBox,
  },
  mixins: [AlgoliaSearch],
  props: {
    color: {
      type: String,
      default: 'primary',
    },
    facets: {
      type: Array,
      default: () => {
        return [
          'domaines',
          'format',
          'template_title',
          'department_name',
          'structure.name',
        ]
      },
    },
    filters: {
      type: [String, Boolean],
      default: false,
    },
  },
  data() {
    return {
      templatesPlaces: {
        value: function (suggestion) {
          return `${suggestion.postcode} ${suggestion.name}`
        },
        suggestion: function (suggestion) {
          let details = [suggestion.county, suggestion.administrative]
          let detailsOutput = ''
          details.forEach((element) => {
            if (element) {
              detailsOutput += ` - <span>${element}</span>`
            }
          })
          return (
            `<div class="text-black font-bold">${suggestion.highlight.name}</div>` +
            `<div class="text-gray-800 text-xs font-light">` +
            `<span>${suggestion.postcode}</span>${detailsOutput}` +
            `</div>`
          )
        },
      },
      showFilters: false,
      isMobile: true,
      windowWidth: window.innerWidth,
      clearExcludes: ['type'],
    }
  },
  computed: {
    modeLight() {
      return process.env.MIX_MODE_APP_LIGHT
        ? JSON.parse(process.env.MIX_MODE_APP_LIGHT)
        : false
    },
    aroundLatLng() {
      return this.routeState && this.routeState.aroundLatLng
        ? this.routeState.aroundLatLng
        : undefined
    },
    aroundRadius() {
      return this.routeState && this.routeState.aroundRadius
        ? parseInt(this.routeState.aroundRadius)
        : this.routeState &&
          this.routeState.refinementList &&
          this.routeState.refinementList.type &&
          this.routeState.refinementList.type[0] == 'Mission en présentiel'
        ? 25000
        : 'all'
    },
    aisFilters() {
      let filters = []
      if (this.type) {
        filters.push(`type:"${this.type}"`)
      }
      if (this.filters) {
        filters.push(`${this.filters}`)
      }

      return filters.join(' AND ')
    },
    type() {
      return this.routeState &&
        this.routeState.refinementList &&
        this.routeState.refinementList.type
        ? this.routeState.refinementList.type[0]
        : null
    },
    placeLabel() {
      return this.routeState && this.routeState.place
        ? this.routeState.place
        : undefined
    },
  },
  mounted() {
    this.sizeListener()
    window.onresize = () => {
      this.sizeListener()
    }
  },
  methods: {
    onQueryClear() {
      this.$delete(this.routeState, 'query')
    },
    scrollToTop() {
      this.$refs.contentWrapper.scrollIntoView()
    },
    sizeListener() {
      this.windowWidth = window.innerWidth
      if (this.windowWidth >= 1024) {
        this.showFilters = true
        this.isMobile = false
      } else {
        this.isMobile = true
      }
    },
    onTypeChanged(type) {
      this.$delete(this.routeState, 'aroundRadius')
      this.$delete(this.routeState, 'aroundLatLng')
      this.$delete(this.routeState, 'place')
      if (!this.routeState.refinementList) {
        this.$set(this.routeState, 'refinementList', {})
      }
      this.$set(this.routeState.refinementList, 'type', [type])

      if (type == 'Mission en présentiel') {
        this.$set(this.routeState, 'aroundRadius', this.aroundRadius)
      }
      this.writeUrl()
    },
    onTypeRemoved() {
      this.$delete(this.routeState, 'aroundRadius')
      this.$delete(this.routeState, 'aroundLatLng')
      this.$delete(this.routeState, 'place')
      this.$delete(this.routeState.refinementList, 'type')
      this.writeUrl()
    },
    onChangeRadius(radius) {
      this.$set(this.routeState, 'aroundRadius', radius)
      this.writeUrl()
    },
  },
}
</script>

<style lang="sass" scoped>
.search-input
  ::v-deep
    input
      border-radius: 8px
      border-color: #EDE8E9
      height: 46px
      color: #171725

::v-deep .ais-Hits-list
  height: max-content
  @apply -m-3 justify-center
  @screen lg
    @apply ml-auto justify-start

::v-deep .ais-Hits-item
  width: 100%
  @apply border-0 shadow-none p-0 m-3
  @screen sm
    width: 292px
  @screen lg
    width: 300px
    @apply flex flex-col

.ais-Pagination
  ::v-deep ul
    li
      color: black
      transition: all .25s
      @apply p-2 border-b-2 border-transparent text-sm
      &.cursor-not-allowed
        color: #908E8E
      &:hover:not(.cursor-not-allowed),
      &.active
        @apply border-primary
    .page-number
      @apply mx-2 font-light hidden
      @screen md
        @apply inline-block
      &.active
        @apply font-bold inline-block

.sort
  width: 180px
  ::v-deep
    .el-input__prefix
      pointer-events: none
      left: 15px
      top: 10px
      font-size: 12px
      color: rgba(255, 255, 255, 0.7)
      letter-spacing: -0.1px
      line-height: 18px
    input
      height: 60px
      padding: 0 15px
      background-color: transparent
      color: white
      letter-spacing: -0.1px
      font-size: 14px
      font-weight: bold
      border: none
      position: relative
      top: 10px
    .el-input
      border: 1px solid white
      border-radius: 8px
      cursor: pointer
    .el-select__caret
      color: white
      font-weight: bold
      font-size: 12px
      position: relative
      right: 10px

.facets--wrapper
  @media screen and (max-width: 1023px)
    position: fixed
    width: 100%
    height: 100%
    z-index: 100
    display: flex
    flex-direction: column
    @apply inset-0 bg-gray-100

.clear-refinements
  box-shadow: 0px 4px 14px 0px rgba(0, 0, 0, 0.05)
  @apply bg-white px-4 py-2 pr-3 rounded-lg text-black text-sm font-semibold mb-8 flex items-center cursor-pointer
  .clear-refinement--icon
    transition: opacity .15s
    @apply absolute m-auto opacity-50
  &:hover
    .clear-refinement--icon
      @apply opacity-100

.header
  &.custom-color
    ::v-deep .el-radio
      color: white
      border-color: white
      .el-radio__label
        color: currentColor
      .el-radio__input
        .el-radio__inner
          background: currentColor
          border-color: currentColor
          &::after
            filter: grayscale(1) invert(1) contrast(.5)
      .el-radio__input
        &.is-checked
          .el-radio__inner
            &::after
              filter: grayscale(1) invert(1)
</style>
