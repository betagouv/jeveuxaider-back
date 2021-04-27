<template>
  <ais-instant-search :search-client="searchClient" :index-name="indexName">
    <ais-configure :hits-per-page.camel="5" />
    <ais-autocomplete>
      <template slot-scope="{ indices, refine }">
        <div class="">
          <vue-autosuggest
            :suggestions="indicesToSuggestions(indices)"
            :get-suggestion-value="getSuggestionValue"
            :input-props="{
              placeholder: placeholder,
            }"
            class="relative"
            @input="onInput(refine, $event)"
            @selected="onSelect"
          >
            <template slot="after-input"
              ><div
                class="absolute z-10 w-5 h-5 text-gray-300"
                style="right: 15px; top: 12px"
                v-html="
                  require('@/assets/images/icones/heroicon/search.svg?include')
                "
              ></div
            ></template>
            <template slot-scope="{ suggestion }">
              <div>
                <div
                  class="ml-auto leading-6 text-sm font-medium text-gray-500 flex-none"
                >
                  <div class="flex items-center space-x-2">
                    <div class="">{{ suggestion.item.name.fr }}</div>
                    <div
                      v-if="isAlreadySelected(suggestion.item.id)"
                      class="px-2 rounded-full text-xxs bg-blue-800 text-white leading-5"
                    >
                      Ajoutée
                    </div>
                  </div>
                  <div class="text-xs italic">
                    {{ suggestion.item.group }}
                  </div>
                </div>
              </div>
            </template>
          </vue-autosuggest>
        </div>
      </template>
    </ais-autocomplete>
  </ais-instant-search>
</template>

<script>
import algoliasearch from 'algoliasearch/lite'
import { VueAutosuggest } from 'vue-autosuggest'
import {
  AisInstantSearch,
  AisConfigure,
  AisAutocomplete,
} from 'vue-instantsearch'

const searchClient = algoliasearch(
  process.env.algolia.appId,
  process.env.algolia.searchKey
)

export default {
  components: {
    VueAutosuggest,
    AisConfigure,
    AisInstantSearch,
    AisAutocomplete,
  },
  props: {
    items: {
      type: Array,
      required: true,
    },
    placeholder: {
      type: String,
      default: 'Communication, action sociale, accompagnement...',
    },
  },
  data() {
    return {
      searchClient,
      indexName: process.env.algolia.skills,
      selectedItem: null,
      query: null,
    }
  },
  methods: {
    isAlreadySelected(id) {
      return this.items.filter((item) => item.id == id).length > 0
    },
    onSelect(selected) {
      if (selected && !this.isAlreadySelected(selected.item.id)) {
        this.selectedItem = selected.item
        this.$emit('add-item', this.selectedItem)
      }
    },
    onInput(refine, $event) {
      this.query = $event
      refine($event)
    },
    indicesToSuggestions(indices) {
      return indices.map(({ hits }) => ({
        data: hits,
      }))
    },
    getSuggestionValue(suggestion) {
      return null
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep #autosuggest
  input
    @apply w-full pl-4 pr-12 py-3 rounded-lg border border-gray-200 text-sm
::v-deep .ais-Highlight-highlighted
  background: transparent
  @apply text-blue-800 font-semibold
::v-deep .autosuggest__results-container
  .autosuggest__results
    max-width: 448px
    @apply w-full rounded-lg absolute z-50 bg-white mt-1 overflow-hidden border border-gray-200
  .autosuggest__results-item
    @apply px-4 py-2
    &:not(:last-child)
      @apply border-b border-gray-100
    &.autosuggest__results-item--highlighted
      @apply cursor-pointer bg-gray-50 text-gray-700
</style>
