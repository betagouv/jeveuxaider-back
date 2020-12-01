<template>
  <div class="el-form-item is-required">
    <label :for="selector" class="el-form-item__label">Lieu</label>
    <item-description container-class="mb-2">
      Si l'adresse n'est pas reconnue veuillez saisir le nom de la ville.
    </item-description>
    <input
      :id="selector"
      type="text"
      class="el-input__inner places-search"
      placeholder="Rechercher une adresse..."
      autocomplete="off"
    />
  </div>
</template>

<script>
let places = require('places.js')
import ItemDescription from '@/components/forms/ItemDescription'

export default {
  components: {
    ItemDescription,
  },
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
  },
  data() {
    return {
      placesInstance: {},
    }
  },
  mounted() {
    const fixedOptions = {
      appId: process.env.MIX_ALGOLIA_PLACES_APP_ID,
      apiKey: process.env.MIX_ALGOLIA_PLACES_API_KEY,
      container: document.querySelector(`#${this.selector}`),
    }

    const reconfigurableOptions = {
      language: 'fr',
      countries: ['fr'],
      // type: 'city',
      aroundLatLngViaIP: false,
      useDeviceLocation: false,
    }

    this.placesInstance = places(fixedOptions).configure(reconfigurableOptions)
    this.placesInstance.setVal(this.value)
    this.placesInstance.on('change', (e) => this.handleSelected(e.suggestion))
    this.placesInstance.on('clear', () => this.resetForm())
  },
  methods: {
    resetForm() {
      this.$emit('clear')
    },
    handleSelected(suggestion) {
      this.$emit('selected', suggestion)
    },
    setVal(value) {
      this.placesInstance.setVal(value)
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
