<template>
  <el-card
    shadow="never"
    class="mb-5 p-5 uppercase"
    :class="{ 'hover:border-blue-900 cursor-pointer': link }"
  >
    <div @click="onClick">
      <div class="label mb-3 text-lg font-bold text-secondary">
        {{ label }}
      </div>
      <template v-if="!$fetchState.pending">
        <div class="count text-primary font-medium text-2xl">
          {{ data.total | formatNumber }}
        </div>
        <div class="flex flex-wrap">
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">En attente</div>
            <div class>
              {{ data.waiting | formatNumber }}
            </div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Validées</div>
            <div class>
              {{ data.validated | formatNumber }}
            </div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Refusées</div>
            <div class>
              {{ data.refused | formatNumber }}
            </div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Effectuées</div>
            <div class>
              {{ data.done | formatNumber }}
            </div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Annulées</div>
            <div class>
              {{ data.canceled | formatNumber }}
            </div>
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
  props: {
    collectivity: {
      type: Object,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    link: {
      type: String,
      required: false,
      default: null,
    },
  },
  data() {
    return {
      data: null,
    }
  },
  async fetch() {
    const statistics = await this.$api.getCollectivityParticipationsStatistics(
      this.collectivity.id
    )
    this.data = statistics.data
  },
  fetchOnServer: false,
  methods: {
    onClick() {
      if (this.link && this.$store.getters.contextRole != 'analyste') {
        this.$router.push(this.link)
      }
    },
  },
}
</script>
