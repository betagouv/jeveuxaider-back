<template>
  <div class="query-filter mr-4 mb-4">
    <div class="text-secondary text-xs uppercase font-semibold mb-2">
      {{ label }}
    </div>
    <el-select
      :value="formattedValue"
      :multiple="multiple"
      clearable
      filterable
      :placeholder="placeholder"
      @change="onChangeFilter"
    >
      <el-option
        v-for="option in options"
        :key="option.value"
        :label="option.label"
        :value="option.value"
      />
    </el-select>
  </div>
</template>

<script>
export default {
  name: 'QueryFilter',
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
      default: 'Choisir',
    },
    options: {
      type: Array,
      required: true,
    },
    multiple: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  computed: {
    formattedValue() {
      if (this.value == 'true') {
        return 'Oui'
      } else if (this.value == 'false') {
        return 'Non'
      }
      if (
        this.multiple &&
        !(this.value instanceof Array) &&
        this.value !== null
      ) {
        return [this.value]
      }

      if (
        this.value &&
        this.options[0] &&
        this.options[0].value &&
        this.options[0].label
      ) {
        return this.options.filter((option) => this.value == option.value)[0]
          .label
      }

      return this.value
    },
  },
  methods: {
    onChangeFilter(value) {
      this.$emit('changed', { name: this.name, value: value })
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
