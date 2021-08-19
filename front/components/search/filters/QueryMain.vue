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
import { debounce, isNull } from 'lodash'

export default {
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
      input: this.initialValue,
      debouncedInput: '',
    }
  },
  watch: {
    input: debounce(function (newVal) {
      if (!isNull(newVal)) {
        this.$emit('changed', { name: this.name, value: newVal })
      }
    }, 500),
  },
}
</script>

<style lang="postcss" scoped>
.query-main-search-filter {
  input {
    @apply bg-gray-100;
    border: none;
  }
  .el-input__inner {
    padding-left: 50px;
  }
  .el-input__prefix {
    padding: 0 10px;
  }
}
</style>
