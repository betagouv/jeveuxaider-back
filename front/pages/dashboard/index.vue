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
      <TabsMain index="main" />
    </div>
    <div class="px-12">
      <ReminderReferent
        v-if="
          $store.getters.reminders && $store.getters.contextRole === 'referent'
        "
        class="mb-6"
      />
      <ReminderResponsable
        v-if="
          $store.getters.reminders &&
          $store.getters.contextRole === 'responsable'
        "
        class="mb-6"
      />
    </div>
    <div class="px-12">
      <div class="flex flex-wrap">
        <CardOccupationRate
          v-if="$store.getters.contextRole != 'responsable'"
        />
        <CardPlacesLeftCount
          v-if="$store.getters.contextRole != 'responsable'"
        />
        <CardDefaultCount
          v-if="$store.getters.contextRole != 'responsable'"
          label="Organisations"
          name="structures"
          link="/dashboard/stats/structures"
        />
        <CardDefaultCount
          label="Missions"
          name="missions"
          link="/dashboard/stats/missions"
        />
        <CardDefaultCount
          label="Participations"
          name="participations"
          link="/dashboard/stats/participations"
        />
        <CardDefaultCount
          v-if="$store.getters.contextRole != 'responsable'"
          label="Utilisateurs"
          name="profiles"
          link="/dashboard/stats/profiles"
        />
        <CardDefaultCount
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
}
</script>
