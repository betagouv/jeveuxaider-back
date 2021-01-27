<template>
  <el-select v-model="aroundRadius" class="sort m-2" @change="onRadiusSelect">
    <div slot="prefix">{{ label }}</div>

    <el-option
      v-for="item in aroundRadiusOptions"
      :key="item.value"
      :label="item.label"
      :value="item.value"
    >
    </el-option>
  </el-select>
</template>

<script>
export default {
  props: {
    label: {
      type: [String],
      default: 'Filtrer par distance',
    },
  },
  data() {
    return {
      aroundRadius: 50000,
      aroundRadiusOptions: [
        { value: 1000, label: 'Moins de 1 km' },
        { value: 5000, label: 'Moins de 5 kms' },
        { value: 25000, label: 'Moins de 25 kms' },
        { value: 50000, label: 'Moins de 50 kms' },
      ],
    }
  },
  methods: {
    onRadiusSelect(value) {
      this.$emit('selected', value)
    },
  },
}
</script>

<style lang="sass">
.places-search
    font-size: 14px!important
.ap-dropdown-menu
    border-radius: 0
.ap-suggestion
    padding-left: 25px
    font-size: 14px
    .ap-suggestion-icon
        display: none
    &.ap-cursor
      @apply bg-gray-100
.ap-footer
    display: none
</style>
