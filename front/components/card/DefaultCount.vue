<template>
  <el-card
    shadow="never"
    class="mr-5 mb-5 p-5 uppercase"
    :class="{ 'hover:border-blue-900 cursor-pointer': link }"
    style="width: 330px"
  >
    <div @click.prevent="onClick">
      <div class="label mb-3 text-lg font-bold text-secondary">
        {{ label }}
      </div>
      <template v-if="!$fetchState.pending">
        <div class="count text-primary font-medium text-2xl">
          {{ data.total | formatNumber }}
        </div>
        <div class="mt-5">
          <div class="my-1">
            <span class>+{{ data.month | formatNumber }}</span>
            <span class="text-xs text-gray-500">les 30 derniers jours</span>
          </div>
          <div class="my-1">
            <span class>+{{ data.week | formatNumber }}</span>
            <span class="text-xs text-gray-500">les 7 derniers jours</span>
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
    const statistics = await this.$api.statistics(this.name, {
      type: 'light',
      role: this.$route.query.role,
    })
    this.data = statistics.data
  },
  fetchOnServer: false,
  methods: {
    onClick() {
      if (this.link) {
        this.$router.push(this.link)
      }
    },
  },
}
</script>
