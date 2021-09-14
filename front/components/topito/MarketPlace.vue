<template>
  <CardTopito
    title="Bénévoles les plus sollicités"
    subtitle="A partir de la market place inversée"
    :loading="loading"
  >
    <template slot="actions"
      ><el-select
        v-model="filters.department"
        placeholder="Tous les départements"
        clearable
        class="w-28"
        size="small"
        @change="onChangeOption"
      >
        <el-option
          v-for="item in $store.getters.taxonomies.departments.terms"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        />
      </el-select>
    </template>
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
              {{ item.count | pluralize(['notification', 'notifications']) }}
            </div>
          </div>
        </div>
        <div class="text-gray-400 font-bold text-sm">
          {{ index + 1 }}
        </div>
      </router-link>
    </ul>
    <div v-else class="flex items-center justify-center p-6">
      <div class="text-gray-500">Pas de résultats</div>
    </div>
  </CardTopito>
</template>

<script>
import CardTopito from '@/components/CardTopito'

export default {
  components: {
    CardTopito,
  },
  props: {
    daterange: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: true,
      items: [],
      filters: { daterange: this.daterange, department: null },
    }
  },
  watch: {
    daterange(newValue, oldValue) {
      this.filters.daterange = newValue
      this.fetch()
    },
  },
  created() {
    this.fetch()
  },
  methods: {
    onChangeOption(value) {
      this.filters.department = value
      this.fetch()
    },
    async fetch() {
      this.loading = true
      const response = await this.$api.fetchTopitoMarketPlace(this.filters)
      this.items = response.data.items
      this.loading = false
    },
  },
}
</script>

<style></style>
