<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord - Domaines
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <dashboard-menu index="domaines" />
    </div>
    <div class="px-12">
      <card-domaine-count label="Domaines" name="domaines" />
    </div>
  </div>
</template>

<script>
import DashboardMenu from '@/components/DashboardMenu'
import CardDomaineCount from '@/components/CardDomaineCount'

export default {
  name: 'DashboardDepartments',
  components: {
    DashboardMenu,
    CardDomaineCount,
  },
  data() {
    return {
      activeIndex: 'domaines',
      loading: false,
    }
  },
  computed: {},
  methods: {},
}
</script>
