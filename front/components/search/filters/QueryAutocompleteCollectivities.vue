<template>
  <div class="query-filter">
    <div class="text-secondary text-xs uppercase font-semibold mb-2">
      {{ label }}
    </div>
    <el-select
      :value="formattedValue"
      clearable
      filterable
      :placeholder="placeholder"
      :loading="loading"
      reserve-keyword
      remote
      :remote-method="fetchOptions"
      @change="onChangeFilter"
    >
      <el-option
        v-for="option in options"
        :key="option.id"
        :label="option.name"
        :value="option.id"
      />
    </el-select>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: [String, Number, Boolean, Array],
      required: false,
      default: null,
    },
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
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
  computed: {
    formattedValue() {
      if (this.$route.query[`option[${this.name}]`] && this.value) {
        return this.$route.query[`option[${this.name}]`]
      }
      return this.value
    },
  },
  methods: {
    async fetchOptions(query) {
      this.loading = true
      const collectivities = await this.$api.fetchCollectivities({
        'filter[search]': query,
        'filter[state]': 'validated',
        pagination: 10,
      })
      this.options = collectivities.data.data
      this.loading = false
    },
    onChangeFilter(value) {
      let query = {
        ...this.$route.query,
        [`filter[${this.name}]`]: value,
        page: 1,
      }

      if (value) {
        query = {
          ...query,
          [`option[${this.name}]`]: this.options.find(
            (option) => option.id == value
          ).name,
        }
      } else {
        delete query[`option[${this.name}]`]
      }

      this.$router.push({
        path: this.$router.history.current.path,
        query,
      })
    },
  },
}
</script>

<style lang="sass">
.query-filter
  .el-tag.el-tag--info
    .el-select__tags-text
      display: inline-flex
      max-width: 120px
      overflow: hidden
</style>
