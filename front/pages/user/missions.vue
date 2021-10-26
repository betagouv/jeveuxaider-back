<template>
  <div class="bg-gray-100 flex flex-col flex-grow">
    <div class="bg-primary pb-32">
      <div class="container mx-auto px-4 pt-10 flex flex-col space-y-4">
        <div class="">
          <h1 class="text-3xl font-bold text-white">
            Missions auxquelles j'ai candidaté
          </h1>
        </div>
        <div v-if="statistics" class="mb-8 hidden md:flex flex-wrap -m-2">
          <div v-for="(statistic, key) in statistics.participations" :key="key">
            <router-link
              v-if="statistic"
              :to="
                key != 'Toutes'
                  ? `/user/missions?filter[state]=${key}`
                  : '/user/missions'
              "
              class="m-2 flex items-center justify-center space-x-1 px-3 py-1 rounded-full text-xs leading-5 font-semibold tracking-wide uppercase truncate cursor-pointer"
              :class="
                isFilterActive('filter[state]', key) ||
                (key == 'Toutes' && !query['filter[state]'])
                  ? 'bg-indigo-100 text-[#1f0391]'
                  : 'bg-gray-100 text-gray-900'
              "
            >
              <span>{{ key }}</span>
              <span class="text-xs font-light">({{ statistic }})</span>
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="-mt-32">
      <div class="container mx-auto px-4 mb-12 mt-8">
        <template v-if="tableData && !tableData.length">
          <div
            class="bg-white rounded-lg shadow-lg overflow-hidden px-6 py-8 lg:p-12"
          >
            Vous n'avez aucune participation pour le moment.
          </div>
        </template>
        <template v-else>
          <div class="flex flex-wrap -m-3">
            <template v-if="!$fetchState.pending">
              <div
                v-for="participation in tableData"
                :key="participation.id"
                class="ais-Hits-item"
              >
                <nuxt-link
                  class="flex flex-col flex-1"
                  :to="
                    participation.conversation
                      ? `/messages/${participation.conversation.id}`
                      : `/missions-benevolat/${participation.mission.id}/${participation.mission.slug}`
                  "
                >
                  <CardMission
                    :participation="participation"
                    :mission="participation.mission"
                    show-state
                  />
                </nuxt-link>
              </div>
            </template>
            <div
              v-else
              class="bg-white w-full rounded-lg shadow-lg overflow-hidden px-6 py-8 lg:p-12 flex items-center justify-center"
              style="height: 294px"
            >
              <IconSpin class="animate-spin h-10 w-10 text-gray-500" />
            </div>
            <div
              v-if="totalRows > 8"
              class="m-3 flex items-center justify-center w-full"
            >
              <el-pagination
                background
                layout="prev, pager, next"
                :total="totalRows"
                :page-size="8"
                :pager-count="5"
                :current-page="Number(query.page)"
                @current-change="onPageChange"
              />
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import TableWithFilters from '@/mixins/table-with-filters'
import IconSpin from '@/components/icons/IconSpin'

export default {
  name: 'UserMissions',
  components: { IconSpin },
  mixins: [TableWithFilters],
  middleware: 'logged',
  async asyncData({ $api, store, params }) {
    const statistics = await $api.fetchProfileStatistics(
      store.getters.user.profile.id
    )
    return {
      statistics,
    }
  },
  data() {
    return {}
  },
  async fetch() {
    const { data } = await this.$api.fetchProfileParticipations(
      this.$store.getters.user.profile.id,
      this.query
    )
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
}
</script>

<style lang="postcss" scoped>
.ais-Hits-item {
  width: 100%;
  @apply border-0 shadow-none p-0 m-3;
  @screen sm {
    width: 292px;
  }
  @screen lg {
    width: 294px;
    @apply flex flex-col;
  }
}
</style>
