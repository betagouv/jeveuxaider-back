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
      <template slot-scope="{ suggestion }"
        >{{ suggestion.item.title }}
      </template>
      <template slot="after-input"
        ><div
          class="absolute z-10 w-5 h-5 text-gray-300"
          style="right: 15px; top: 12px"
          v-html="require('@/assets/images/icones/heroicon/search.svg?include')"
        ></div
      ></template>
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
      query: null,
      selected: null,
      suggestions: [],
    }
  },
  methods: {
    onSelected(item) {
      console.log('onSelected', item)
      // this.selected = item.item
    },
    onInputChange(text) {
      // event fired when the input changes
      console.log(text)
      this.search()
    },
    /**
     * This is what the <input/> value is set to when you are selecting a suggestion.
     */
    getSuggestionValue(suggestion) {
      console.log('getSuggestionValue', suggestion)
      return 'getSuggestionValue'
    },
    async search() {
      const res = await this.$axios.post(
        'https://api.api-engagement.beta.gouv.fr/v0/association/search',
        {
          query: this.query,
        }
      )
      console.log('res', res.data)
      this.suggestions = [{ data: res.data }]
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
