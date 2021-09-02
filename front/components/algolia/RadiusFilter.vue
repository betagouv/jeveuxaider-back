<template>
  <ElSelect v-model="aroundRadius" class="sort m-2" @change="onRadiusSelect">
    <ElOption
      v-for="item in aroundRadiusOptions"
      :key="item.value"
      :label="item.label"
      :value="item.value"
    >
    </ElOption>
  </ElSelect>
</template>

<script>
export default {
  props: {
    initialValue: {
      type: [Number, String],
      default: 'all',
    },
  },
  data() {
    return {
      aroundRadius: this.initialValue,
      aroundRadiusOptions: [
        { value: 'all', label: 'Aucun' },
        { value: 1000, label: '1km' },
        { value: 5000, label: '5km' },
        { value: 25000, label: '25km' },
        { value: 35000, label: '35km' },
        { value: 50000, label: '50km' },
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
