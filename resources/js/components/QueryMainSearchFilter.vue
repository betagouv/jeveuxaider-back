<template>
  <div class="query-main-search-filter flex-1">
    <el-input
      ref="input"
      v-model="input"
      class=""
      prefix-icon="el-icon-search"
      :placeholder="placeholder"
      clearable
    />
  </div>
</template>

<script>
import _ from 'lodash'

export default {
  name: 'QueryMainSearchFilter',
  props: {
    initialValue: {
      type: String,
      required: false,
      default: null,
    },
    name: {
      type: String,
      required: true,
    },
    placeholder: {
      type: String,
      required: false,
      default: 'Rechercher par mots clés...',
    },
  },
  data() {
    return {
      test: true,
      input: this.initialValue,
      debouncedInput: '',
    }
  },
  watch: {
    input: _.debounce(function (newVal) {
      if (!_.isNull(newVal)) {
        this.$emit('changed', { name: this.name, value: newVal })
      }
    }, 500),
  },
  mounted() {
    if (this.initialValue) {
      this.$refs.input.focus()
    }
  },
}
</script>

<style lang="sass">

.query-main-search-filter
  input
    @apply bg-gray-100
    border: none
  .el-input__inner
    padding-left: 50px
  .el-input__prefix
    padding: 0 10px
</style>
