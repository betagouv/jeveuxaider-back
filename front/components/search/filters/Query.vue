<template>
  <div class="query-filter">
    <div class="text-secondary text-xs uppercase font-semibold mb-2">
      {{ label }}
    </div>
    <el-select
      :value="formattedValue"
      :multiple="multiple"
      clearable
      filterable
      :allow-create="allowCreate"
      :default-first-option="defaultFirstOption"
      :placeholder="placeholder"
      :popper-class="popperClasses"
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
    allowCreate: {
      type: Boolean,
      required: false,
      default: false,
    },
    defaultFirstOption: {
      type: Boolean,
      required: false,
      default: false,
    },
    popperClasses: {
      type: String,
      default: '',
    },
  },
  computed: {
    formattedValue() {
      if (this.value == 'true') {
        return 'Oui'
      } else if (this.value == 'false') {
        return 'Non'
      }
      if (this.multiple && !Array.isArray(this.value) && this.value !== null) {
        return [this.value]
      }

      if (
        !this.multiple &&
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
      this.$emit('changed', { name: this.name, value })
    },
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
</style>
