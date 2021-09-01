<template>
  <CardTopito title="Bénévoles du moment" :loading="loading">
    <ul v-if="items.length" class="divide-y divide-gray-200">
      <router-link
        v-for="(item, index) in items"
        :key="item.id"
        class="p-4 flex justify-between items-center hover:bg-gray-50 cursor-pointer"
        :to="`/dashboard/profile/${item.id}`"
        target="_blank"
      >
        <div class="flex">
          <Avatar
            :source="item.image ? item.image.thumb : null"
            :fallback="item.short_name"
          />
          <div class="ml-3">
            <div class="text-sm font-medium text-gray-900">
              {{ item.full_name }}
            </div>
            <div class="text-sm text-gray-500">
              {{ item.count | formatNumber }}
              {{ item.count | pluralize(['candidature', 'candidatures']) }}
            </div>
          </div>
        </div>
        <div class="text-gray-400 font-bold text-sm">
          {{ index + 1 }}
        </div>
      </router-link>
    </ul>
  </CardTopito>
</template>

<script>
import CardTopito from '@/components/CardTopito'

export default {
  components: {
    CardTopito,
  },
  data() {
    return {
      loading: true,
      items: [],
    }
  },
  created() {
    this.fetch({ daterange: 'current-month' })
  },
  methods: {
    async fetch(filters) {
      this.loading = true
      const response = await this.$api.fetchTopitoBenevolesDuMoment(filters)
      this.items = response.data.items
      this.loading = false
    },
  },
}
</script>

<style></style>
