<template>
  <CardTopito title="Organisations les plus attrayantes" :loading="loading">
    <ul v-if="items.length" class="divide-y divide-gray-200">
      <li
        v-for="(item, index) in items"
        :key="item.id"
        class="p-4 flex justify-between items-center"
      >
        <div class="flex">
          <Avatar
            :source="item.logo ? item.logo.thumb : null"
            :fallback="item.name[0]"
          />
          <div class="ml-3">
            <div class="text-sm font-medium text-gray-900">
              {{ item.name }}
            </div>
            <div class="text-sm text-gray-500">
              {{ item.count }} candidatures
            </div>
          </div>
        </div>
        <div class="text-gray-400 font-bold text-sm">
          {{ index + 1 }}
        </div>
      </li>
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
  async created() {
    const response = await this.$api.fetchTopitoOrganisationsParticipations()
    this.items = response.data.items
    this.loading = false
  },
}
</script>

<style></style>
