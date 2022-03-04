<template>
  <div class="query-filter-skills">
    <div class="text-secondary text-xs uppercase font-semibold mb-2">
      {{ label }}
    </div>
    <el-select
      :value="value"
      value-key="id"
      clearable
      filterable
      reserve-keyword
      remote
      :remote-method="fetchReseaux"
      placeholder="Rechercher par mots clés..."
      :loading="loading"
      @change="onChangeFilter"
    >
      <el-option
        v-for="item in optionsReseaux"
        :key="item.id"
        :label="item.name"
        :value="item"
      />
    </el-select>
  </div>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      skills: null,
      optionsReseaux: [],
      value: {},
    }
  },
  async created() {
    if (this.$route.query && this.$route.query['filter[of_reseau]']) {
      const { data: reseaux } = await this.$api.fetchReseaux({
        'filter[id]': this.$route.query['filter[of_reseau]'],
      })
      this.optionsReseaux = reseaux.data
      this.value = reseaux.data[0]
    }
  },
  methods: {
    onChangeFilter(reseau) {
      this.value = reseau
      this.$emit('changed', { name: this.name, value: reseau.id })
    },
    async fetchReseaux(query) {
      this.loading = true
      const { data: reseaux } = await this.$api.fetchReseaux({
        'filter[name]': query,
      })
      this.optionsReseaux = reseaux.data
      this.loading = false
    },
  },
}
</script>
