<template>
  <div>
    <vue-autosuggest
      ref="autocompleterna"
      v-model="query"
      :suggestions="suggestions"
      :get-suggestion-value="getSuggestionValue"
      :input-props="{
        placeholder: 'Nom de votre organisation',
        autocomplete: 'new-password',
      }"
      class="relative w-full leading-none"
      :limit="5"
      :loading="renderSuggestions"
      @input="onInputChange"
      @selected="onSelected"
      @keydown.tab="onTab"
      @focus="onFocus"
    >
      <template slot="before-section-default">
        <div
          class="
            text-md text-gray-700
            py-3
            px-4
            font-bold
            uppercase
            bg-gray-200
            tracking-wider
          "
        >
          Suggestions
        </div>
      </template>

      <template slot-scope="{ suggestion }">
        <div class="mb-1">{{ suggestion.item.name }}</div>
        <div class="text-xs text-gray-500">RNA {{ suggestion.item.rna }}</div>
        <div
          v-if="suggestion.item.coordonnees.adresse"
          class="text-xs text-gray-500"
        >
          {{ suggestion.item.coordonnees.adresse.nom_complet }}
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
      renderSuggestions: true,
    }
  },
  methods: {
    onSelected(item) {
      if (item) {
        this.selected = item.item
        this.$emit('change', this.selected.name)
        this.$emit('selected', this.selected)
      }
    },
    onInputChange(text) {
      this.$emit('change', text)
      this.$emit('clear')
      this.search()
    },
    onFocus() {
      document.getElementById(
        'autosuggest-autosuggest__results'
      ).style.display = 'block'
    },
    onTab() {
      this.$refs.autocompleterna.listeners.selected(true)
      document.getElementById(
        'autosuggest-autosuggest__results'
      ).style.display = 'none'
    },
    getSuggestionValue(suggestion) {
      return suggestion.item.name
    },
    async search() {
      this.loading = true
      const res = await this.$axios.post(
        'https://api.api-engagement.beta.gouv.fr/v0/association/search',
        {
          name: this.query,
        },
        {
          headers: {
            apikey: this.$config.apieng.key,
          },
        }
      )
      this.loading = false
      this.suggestions = [{ data: res.data.data }]
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
  .autosuggest__results ul
    max-width: 480px
    @apply w-full rounded-lg absolute z-50 bg-white mt-1 overflow-hidden border border-gray-200
  .autosuggest__results-item
    @apply px-4 py-3
    &:not(:last-child)
      @apply border-b border-gray-100
    &.autosuggest__results-item--highlighted
      @apply cursor-pointer bg-gray-50 text-gray-700
</style>
