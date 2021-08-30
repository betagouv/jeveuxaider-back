<template>
  <div>
    <div class="grid grid-cols-1">
      <el-input
        ref="input"
        v-model="search"
        class="mb-3"
        prefix-icon="el-icon-search"
        placeholder="Rechercher..."
        clearable
        autocomplete="password"
        @input="onInputChange"
      />

      <el-input
        ref="input-city"
        v-model="city"
        class="mb-8"
        prefix-icon="el-icon-position"
        placeholder="Ville..."
        autocomplete="password"
        clearable
        @input="onInputChange"
      />
    </div>
    <div v-if="suggestions">
      <div
        v-for="suggestion in suggestions"
        :key="suggestion.rna"
        class="bg-white p-4 mb-4 rounded-lg hover:border-blue-800 cursor-pointer"
        :class="isSelected(suggestion) ? 'border-2 border-blue-800 ' : 'border'"
        @click="onSelected(suggestion)"
      >
        <div
          class="mb-1"
          :class="isSelected(suggestion) ? 'text-blue-800 ' : ''"
        >
          {{ suggestion.name }}
          <span class="text-gray-500 text-xs"
            >({{ suggestion.score.toFixed(2) }})</span
          >
        </div>
        <div class="text-xs text-gray-500">RNA {{ suggestion.rna }}</div>
        <div
          v-if="suggestion.coordonnees.adresse"
          class="text-xs text-gray-500"
        >
          {{ suggestion.coordonnees.adresse.nom_complet }}
        </div>
      </div>
    </div>
    <!-- <div
      v-else
      class="bg-white p-4 mb-4 rounded-lg hover:border-blue-800 cursor-pointer"
    >
      <div class="text-sm text-gray-500">Aucun résultat provenant de l'API</div>
    </div> -->
  </div>
</template>

<script>
export default {
  props: {
    initialSearch: {
      type: String,
      required: false,
      default: null,
    },
    initialCity: {
      type: String,
      required: false,
      default: null,
    },
    structure: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      search: this.initialSearch,
      city: this.initialCity,
      selected: null,
      suggestions: [],
    }
  },
  watch: {
    initialSearch: {
      immediate: true,
      handler(newValue, oldValue) {
        if (newValue) {
          this.reset()
          this.search = newValue
          this.fetchResults()
        }
      },
    },
    initialCity: {
      immediate: true,
      handler(newValue, oldValue) {
        if (newValue) {
          this.reset()
          this.city = newValue
          this.fetchResults()
        }
      },
    },
  },
  methods: {
    reset() {
      this.selected = null
      this.suggestions = []
    },
    isSelected(suggestion) {
      if (this.selected) {
        return this.selected.rna == suggestion.rna
      }
      if (this.structure) {
        return this.structure.rna == suggestion.rna
      }
      return false
    },
    onSelected(suggestion) {
      this.selected = suggestion
      this.$emit('selected', this.selected)
    },
    onInputChange(text) {
      // console.log('onInputChange', text)
      this.fetchResults()
    },
    // onSubmit() {
    //   this.$emit('submitted', this.selected)
    // },
    async fetchResults() {
      this.loading = true
      if (this.search || this.city) {
        const params = {}
        params.name = this.search
        if (this.search) {
          params.name = this.search
        }
        if (this.city) {
          params.city = this.city
        }
        const res = await this.$axios.post(
          'https://api.api-engagement.beta.gouv.fr/v1/association/search',
          params,
          {
            headers: {
              apikey: this.$config.apieng.key,
            },
          }
        )
        this.loading = false
        this.suggestions = res.data.data
      } else {
        this.suggestions = []
      }
    },
  },
}
</script>

<style lang="sass" scoped></style>
