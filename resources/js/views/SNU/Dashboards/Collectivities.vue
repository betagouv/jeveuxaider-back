<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord - Collectivités
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <dashboard-menu index="collectivities" />
    </div>
    <div class="px-12">
      <card-collectivity-count label="Collectivités" name="collectivities" />
    </div>
  </div>
</template>

<script>
import DashboardMenu from '@/components/DashboardMenu'
import CardCollectivityCount from '@/components/CardCollectivityCount'

export default {
  name: 'DashboardCollectivities',
  components: {
    DashboardMenu,
    CardCollectivityCount,
  },
  data() {
    return {
      type: 'Collectivités',
      activeIndex: 'collectivities',
      loading: false,
    }
  },
  computed: {},
  methods: {},
}
</script>
