<template>
  <el-card
    shadow="never"
    class="mr-5 mb-5 p-5 uppercase hover:border-blue-900"
    style="width: 330px"
  >
    <div>
      <div class="label mb-3 text-lg font-bold text-secondary">
        Taux d'occupation
      </div>
      <template v-if="!$fetchState.pending">
        <div class="count text-primary font-medium text-2xl">
          {{ data.occupation_rate | formatNumber }}%
        </div>
        <div class="mt-5">
          <div class="my-1">
            <span class>{{ data.total_offered_places | formatNumber }}</span>
            <span class="text-xs text-gray-500">places offertes</span>
          </div>
        </div>
      </template>
      <template v-else>
        <i class="el-icon-loading" />
      </template>
    </div>
  </el-card>
</template>

<script>
export default {
  data() {
    return {
      data: null,
    }
  },
  async fetch() {
    const statistics = await this.$api.statistics('occupation-rate')
    this.data = statistics.data
  },
  fetchOnServer: false,
  methods: {},
}
</script>
