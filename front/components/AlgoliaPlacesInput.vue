<template>
  <div class="el-form-item is-required">
    <label v-if="label" :for="selector" class="el-form-item__label">
      {{ label }}
    </label>

    <item-description v-if="description" container-class="mb-2">
      {{ description }}
    </item-description>

    <input
      :id="selector"
      type="text"
      class="el-input__inner places-search"
      :placeholder="placeholder"
    />
  </div>
</template>

<script>
const places = require('places.js')

export default {
  props: {
    value: {
      type: String,
      required: false,
      default: '',
    },
    selector: {
      type: String,
      required: false,
      default: 'places-search',
    },
    label: {
      type: [Boolean, String],
      default: 'Lieu',
    },
    description: {
      type: [Boolean, String],
      default:
        "Si l'adresse n'est pas reconnue veuillez saisir le nom de la ville.",
    },
    placeholder: {
      type: [Boolean, String],
      default: 'Rechercher une adresse...',
    },
    type: {
      type: [Boolean, String],
      default: null,
    },
    limit: {
      type: [Boolean, Number],
      default: null,
    },
    templates: {
      type: [Object, Boolean],
      default: null,
    },
  },
  data() {
    return {
      placesInstance: {},
    }
  },
  mounted() {
    let fixedOptions = {
      appId: this.$config.algolia.appId,
      apiKey: this.$config.algolia.apiKey,
      container: document.querySelector(`#${this.selector}`),
    }

    if (this.templates) {
      fixedOptions = { ...fixedOptions, templates: this.templates }
    }

    let reconfigurableOptions = {
      language: 'fr',
      countries: ['fr'],
      aroundLatLngViaIP: false,
      useDeviceLocation: false,
    }

    if (this.type) {
      reconfigurableOptions = { ...reconfigurableOptions, type: this.type }
    }

    if (this.limit) {
      reconfigurableOptions = {
        ...reconfigurableOptions,
        hitsPerPage: this.limit,
      }
    }

    this.placesInstance = places(fixedOptions).configure(reconfigurableOptions)
    this.placesInstance.setVal(this.value)
    this.placesInstance.autocomplete[0].setAttribute('autocomplete', 'off')
    this.placesInstance.on('change', (e) => this.handleSelected(e.suggestion))
    this.placesInstance.on('clear', () => this.resetForm())
    this.placesInstance.on('suggestions', (e) => this.handleSuggestions(e))
  },
  methods: {
    resetForm() {
      this.$emit('clear')
    },
    handleSelected(suggestion) {
      this.placesInstance.autocomplete[0].blur()
      this.$emit('selected', suggestion)
    },
    setVal(value) {
      this.placesInstance.setVal(value)
    },
    handleSuggestions(e) {
      // console.log(e)
    },
  },
}
</script>

<style lang="sass">
.places-search
  font-size: 14px!important
.ap-dropdown-menu
  border-radius: 0
.ap-suggestion
  padding-left: 25px
  font-size: 14px
  .ap-suggestion-icon
    display: none
    &.ap-cursor
      @apply bg-gray-100
.ap-footer
  display: none
</style>
