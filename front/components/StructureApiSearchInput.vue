<template>
  <div>
    <vue-autosuggest
      v-model="query"
      :suggestions="suggestions"
      :get-suggestion-value="getSuggestionValue"
      :input-props="{
        placeholder: 'Nom de votre organisation',
      }"
      class="relative w-full leading-none"
      @input="onInputChange"
      @selected="onSelected"
    >
      <template slot-scope="{ suggestion }">
        <div class="mb-1">{{ suggestion.item['_source'].identite_nom }}</div>
        <div class="text-xs text-gray-500">
          RNA {{ suggestion.item._source.id_rna }}
        </div>
      </template>
      <template slot="after-input">
        <div
          v-if="loading"
          class="absolute z-10 w-5 h-5 text-gray-300 animate-spin"
          style="right: 15px; top: 13px"
          v-html="require('@/assets/images/icones/spinner.svg?include')"
        ></div>
        <div
          v-else
          class="absolute z-10 w-5 h-5 text-gray-300"
          style="right: 15px; top: 13px"
          v-html="require('@/assets/images/icones/heroicon/search.svg?include')"
        ></div>
      </template>
    </vue-autosuggest>
  </div>
</template>

<script>
import { VueAutosuggest } from 'vue-autosuggest'

export default {
  components: {
    VueAutosuggest,
  },
  data() {
    return {
      loading: false,
      query: null,
      selected: null,
      suggestions: [],
    }
  },
  methods: {
    onSelected(item) {
      if (item) {
        this.selected = item.item._source
        this.$emit('selected', this.selected)
      }
    },
    onInputChange(text) {
      this.$emit('change', text)
      this.$emit('clear')
      this.search()
    },
    getSuggestionValue(suggestion) {
      return suggestion.item._source.identite_nom
    },
    async search() {
      this.loading = true
      const url =
        'https://api.api-engagement.beta.gouv.fr/v0/association/search'
      const res = await this.$axios.post(url, {
        query: this.query,
      })
      this.loading = false
      this.suggestions = [{ data: res.data.hits.hits }]
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
    max-width: 480px
    @apply w-full rounded-lg absolute z-50 bg-white mt-1 overflow-hidden border border-gray-200
  .autosuggest__results-item
    @apply px-4 py-3
    &:not(:last-child)
      @apply border-b border-gray-100
    &.autosuggest__results-item--highlighted
      @apply cursor-pointer bg-gray-50 text-gray-700
</style>
