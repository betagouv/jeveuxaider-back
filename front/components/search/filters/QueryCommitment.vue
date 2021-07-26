<template>
  <div class="query-search-filter">
    <div class="text-secondary text-xs uppercase font-semibold mb-2">
      {{ label }}
    </div>

    <div class="flex items-center gap-2">
      <el-select
        v-model="duration"
        :placeholder="placeholderDuration"
        clearable
        class="w-44"
        @change="onChangeFilter"
      >
        <el-option
          v-for="item in $store.getters.taxonomies.duration.terms"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        />
      </el-select>

      <span class="flex-none text-xs text-gray-696974"> par </span>

      <el-select
        v-model="timePeriod"
        :placeholder="placeholderPeriod"
        clearable
        class="w-28"
        @change="onChangeFilter"
      >
        <el-option
          v-for="item in $store.getters.taxonomies.time_period.terms"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        />
      </el-select>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: 'Fréquence',
    },
    placeholderDuration: {
      type: String,
      default: 'Choisir une durée',
    },
    placeholderPeriod: {
      type: String,
      default: ' - ',
    },
    value: {
      type: String,
      default: '',
    },
    name: {
      type: String,
      required: true,
    },
  },
  data() {
    const duration = this.value?.split(',')?.[0]
    const timePeriod = this.value?.split(',')?.[1] ?? null
    return {
      duration,
      timePeriod,
    }
  },
  methods: {
    onChangeFilter() {
      this.$emit('changed', {
        name: this.name,
        value: this.timePeriod
          ? `${this.duration},${this.timePeriod}`
          : this.duration,
      })
    },
  },
}
</script>
