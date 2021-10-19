<template>
  <div>
    <el-select
      :value="selected"
      filterable
      :placeholder="placeholder"
      :loading="loading"
      remote
      :remote-method="fetchOptions"
      value-key="id"
      @change="onChangeFilter"
    >
      <el-option
        v-for="option in options"
        :key="option.id"
        :label="option.name"
        :value="option"
      />
    </el-select>
  </div>
</template>

<script>
export default {
  props: {
    selected: {
      type: Object,
      required: false,
      default: null,
    },
    placeholder: {
      type: String,
      required: false,
      default: 'Mots clés...',
    },
  },
  data() {
    return {
      options: [],
      loading: false,
    }
  },
  async fetch() {
    const { data: structures } = await this.$api.fetchStructures({
      'filter[search]': this.selected?.name,
      // 'filter[state]': 'Validée',
      pagination: 10,
    })
    this.options = structures.data
  },
  methods: {
    // TODO dynamic filters
    async fetchOptions(query) {
      this.loading = true
      const { data: structures } = await this.$api.fetchStructures({
        'filter[search]': query,
        // 'filter[state]': 'Validée',
        pagination: 10,
      })
      this.options = structures.data
      this.loading = false
    },
    onChangeFilter(value) {
      this.$emit('change', value)
    },
  },
}
</script>
