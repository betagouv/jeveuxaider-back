<template>
  <div class="el-form-item" :class="className">
    <label v-if="label" :for="selector" class="el-form-item__label">
      {{ label }}
    </label>

    <ItemDescription v-if="description" container-class="mb-2">
      {{ description }}
    </ItemDescription>

    <input
      :id="selector"
      type="text"
      autocomplete="off"
      class="el-input__inner places-search"
      :placeholder="placeholder"
    />
  </div>
</template>

<script>
export default {
  props: {
    initialValue: {
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
    className: {
      type: [Boolean, String],
      default: 'is-required',
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
      value: this.initialValue,
    }
  },
  watch: {
    initialValue(newVal) {
      this.value = newVal
      this.placesInstance.setVal(newVal)
      this.placesInstance.close()
    },
  },
  mounted() {
    const places = require('places.js')

    let fixedOptions = {
      appId: this.$config.algolia.placesAppId,
      apiKey: this.$config.algolia.placesApiKey,
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

    this.$emit('initialized')
  },
  methods: {
    resetForm() {
      this.$emit('clear')
    },
    handleSelected(suggestion) {
      this.placesInstance.autocomplete[0].blur()
      this.$emit('selected', suggestion)
    },
    handleSuggestions(e) {
      // console.log(e)
    },
  },
}
</script>

<style lang="sass">
.places-search
  @apply text-base
  @screen md
    font-size: 14px !important
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
