<template>
  <div class="query-search-filter">
    <div class="text-secondary text-xs uppercase font-semibold mb-2">
      {{ label }}
    </div>
    <el-input
      :ref="name"
      v-model="input"
      :placeholder="placeholder"
      :type="type"
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
    label: {
      type: String,
      required: true,
    },
    placeholder: {
      type: String,
      required: false,
      default: 'Choisir',
    },
    type: {
      type: String,
      required: false,
      default: 'text',
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
  mounted() {
    if (this.initialValue) {
      if (this.$refs[this.name]) {
        this.$refs[this.name].focus()
      }
    }
  },
}
</script>

<style lang="postcss">
.query-filter {
  .el-tag.el-tag--info {
    .el-select__tags-text {
      display: inline-flex;
      max-width: 120px;
      overflow: hidden;
    }
  }
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type='number'] {
  -moz-appearance: textfield;
}
</style>
