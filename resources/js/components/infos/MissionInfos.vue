<template>
  <div class="text-xs text-gray-600 relative">
    <div v-if="mission.state" class="mb-2 flex">
      <div class="card-label">Statut</div>
      <div class="text-gray-900 flex-1">{{ mission.state }}</div>
    </div>
    <div v-if="mission.type" class="mb-2 flex">
      <div class="card-label">Type</div>
      <div class="text-gray-900 flex-1">
        {{ mission.type }}
      </div>
    </div>
    <div v-if="mission.format" class="mb-2 flex">
      <div class="card-label">Format</div>
      <div class="text-gray-900 flex-1">
        {{ mission.format }}
      </div>
    </div>
    <div v-if="mission.start_date" class="mb-2 flex">
      <div class="card-label">Début</div>
      <div class="text-gray-900 flex-1">
        {{ mission.start_date | formatLong }}
      </div>
    </div>
    <div v-if="mission.end_date" class="mb-2 flex">
      <div class="card-label">Fin</div>
      <div class="text-gray-900 flex-1">
        {{ mission.end_date | formatLong }}
      </div>
    </div>
    <div v-if="mission.full_address.length > 0" class="mb-2 flex">
      <div class="card-label">Adresse</div>
      <div class="text-gray-900 flex-1">{{ mission.full_address }}</div>
    </div>
    <template v-if="mission.tuteur">
      <div v-if="mission.tuteur" class="mb-2 flex">
        <div class="card-label">Responsable</div>
        <div class="text-gray-900 flex-1">
          {{ mission.tuteur.first_name }} {{ mission.tuteur.last_name }}
        </div>
      </div>
      <div v-if="mission.tuteur.mobile" class="mb-2 flex">
        <div class="card-label">Portable</div>
        <div class="text-gray-900 flex-1">
          {{ mission.tuteur.mobile }}
        </div>
      </div>
      <div v-if="mission.tuteur.phone" class="mb-2 flex">
        <div class="card-label">Téléphone</div>
        <div class="text-gray-900 flex-1">
          {{ mission.tuteur.phone }}
        </div>
      </div>
    </template>
    <div v-if="mission.participations_max" class="mb-2 flex">
      <div class="card-label">Max.</div>
      <div class="text-gray-900 flex-1">
        {{ mission.participations_max }}
      </div>
    </div>
    <div v-if="mission.description" class="mb-2 flex">
      <div class="card-label">Détails</div>
      <div class="text-gray-900 flex-1">
        {{ mission.description }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MissionInfos",
  props: {
    mission: {
      type: Object,
      required: true
    }
  },
  data() {
    return {};
  },
  created() {},

  methods: {}
};
</script>
