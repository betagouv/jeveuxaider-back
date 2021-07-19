<template>
  <div class="query-search-filter mr-4 mb-4">
    <div class="text-secondary text-xs uppercase font-semibold mb-2">
      {{ label }}
    </div>

    <div class="flex items-center gap-4">
      <el-input
        v-model="hours"
        :step="1"
        :min="1"
        step-strictly
        :controls="false"
        class="flex-none w-16"
        @keypress.enter.native.prevent="setcommitmentFilters"
      ></el-input>

      <span class="flex-none text-xs text-gray-696974">
        {{ hours | pluralize(['heure par', 'heures par']) }}
      </span>

      <el-select
        v-model="timePeriod"
        placeholder="Choisir une fréquence"
        clearable
        class="w-52"
        @change="setcommitmentFilters"
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
    minimumCommitment: {
      type: String,
      default: '1',
    },
  },
  data() {
    const hours = this.minimumCommitment.split(',')?.[0]
    const timePeriod = this.minimumCommitment.split(',')?.[1] ?? null
    return {
      hours: hours ? parseInt(hours) : 1,
      timePeriod,
    }
  },
  methods: {
    setcommitmentFilters() {
      const query = this.timePeriod
        ? `${this.hours},${this.timePeriod}`
        : this.hours

      this.$router.push({
        path: this.$router.history.current.path,
        query: {
          ...this.query,
          [`filter[minimum_commitment]`]: query,
          page: 1,
        },
      })
    },
  },
}
</script>
