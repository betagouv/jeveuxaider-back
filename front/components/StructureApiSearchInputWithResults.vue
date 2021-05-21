<template>
  <div>
    <el-input
      ref="input"
      v-model="query"
      class="mb-8"
      prefix-icon="el-icon-search"
      placeholder="Rechercher sur l'API Engagement"
      clearable
      @input="onInputChange"
    />

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
          v-if="suggestion.coordonnees.adresse_siege"
          class="text-xs text-gray-500"
        >
          {{ suggestion.coordonnees.adresse_siege.num_voie }}
          {{ suggestion.coordonnees.adresse_siege.type_voie }}
          {{ suggestion.coordonnees.adresse_siege.voie }}
          {{ suggestion.coordonnees.adresse_siege.cp }}
          {{ suggestion.coordonnees.adresse_siege.commune }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    initialValue: {
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
      query: this.initialValue,
      selected: null,
      suggestions: [],
    }
  },
  watch: {
    initialValue: {
      immediate: true,
      handler(newValue, oldValue) {
        if (newValue) {
          this.reset()
          this.query = newValue
          this.search()
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
      console.log('onInputChange', text)
      this.search()
    },
    // onSubmit() {
    //   this.$emit('submitted', this.selected)
    // },
    async search() {
      this.loading = true
      if (this.query) {
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
        this.suggestions = res.data.data
      } else {
        this.suggestions = []
      }
    },
  },
}
</script>

<style lang="sass" scoped></style>
