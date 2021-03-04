<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <DashboardTabsMain index="main" />
    </div>
    <div class="px-12">
      <!-- <reminder-referent
        v-if="$store.getters.contextRole === 'referent'"
        class="mb-12"
      />
      <reminder-responsable
        v-if="$store.getters.contextRole === 'responsable'"
        class="mb-12"
      /> -->
    </div>
    <div class="px-12">
      <div class="flex flex-wrap">
        <DashboardCardOccupationRate
          v-if="$store.getters.contextRole != 'responsable'"
        />
        <DashboardCardPlacesLeftCount
          v-if="$store.getters.contextRole != 'responsable'"
        />
        <DashboardCardDefaultCount
          v-if="$store.getters.contextRole != 'responsable'"
          label="Organisations"
          name="structures"
          link="/dashboard/stats/structures"
        />
        <DashboardCardDefaultCount
          label="Missions"
          name="missions"
          link="/dashboard/stats/missions"
        />
        <DashboardCardDefaultCount
          label="Participations"
          name="participations"
          link="/dashboard/stats/participations"
        />
        <DashboardCardDefaultCount
          v-if="$store.getters.contextRole != 'responsable'"
          label="Utilisateurs"
          name="profiles"
          link="/dashboard/stats/profiles"
        />
        <DashboardCardDefaultCount
          v-if="$store.getters.contextRole != 'responsable'"
          label="En ligne"
          name="online"
          link="/dashboard/stats/profiles"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  middleware: 'admin',
}
</script>
