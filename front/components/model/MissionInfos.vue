<template>
  <div class="text-xs text-gray-600 relative">
    <div class="mb-2 flex">
      <div class="card-label">Id</div>
      <div class="text-gray-900 flex-1">
        {{ mission.id }}
      </div>
    </div>
    <div v-if="!['Signalée'].includes(mission.state)" class="mb-2 flex">
      <div class="card-label">Page</div>

      <nuxt-link
        class="text-gray-900 flex-1 hover:underline truncate"
        target="_blank"
        :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
      >
        /missions-benevolat/{{ mission.id }}/{{ mission.slug }}
      </nuxt-link>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Organisation</div>
      <div class="text-gray-900 flex-1">
        {{ mission.structure.name }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Créée le</div>
      <div class="text-gray-900 flex-1">
        {{ mission.created_at | formatMediumWithTime }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Modifiée le</div>
      <div class="text-gray-900 flex-1">
        {{ mission.updated_at | formatMediumWithTime }}
      </div>
    </div>
    <div v-if="mission.state" class="mb-2 flex">
      <div class="card-label">Statut</div>
      <div class="text-gray-900 flex-1">
        {{ mission.state }}
      </div>
    </div>
    <div v-if="mission.type" class="mb-2 flex">
      <div class="card-label">Type</div>
      <div class="text-gray-900 flex-1">
        {{ mission.type }}
      </div>
    </div>
    <div v-if="mission.start_date" class="mb-2 flex">
      <div class="card-label">Début</div>
      <div class="text-gray-900 flex-1">
        {{ mission.start_date | formatLongWithTime }}
      </div>
    </div>
    <div v-if="mission.end_date" class="mb-2 flex">
      <div class="card-label">Fin</div>
      <div class="text-gray-900 flex-1">
        {{ mission.end_date | formatLongWithTime }}
      </div>
    </div>
    <div v-if="mission.full_address.length > 0" class="mb-2 flex">
      <div class="card-label">Adresse</div>
      <div class="text-gray-900 flex-1">
        {{ mission.full_address }}
      </div>
    </div>
    <div v-if="mission.department" class="mb-2 flex">
      <div class="card-label">Département</div>
      <div class="text-gray-900 flex-1">
        {{ mission.department | fullDepartmentFromValue }}
      </div>
    </div>
    <template v-if="mission.responsable">
      <div v-if="mission.responsable" class="mb-2 flex">
        <div class="card-label">Responsable</div>
        <div class="text-gray-900 flex-1">
          {{ mission.responsable.first_name }}
          {{ mission.responsable.last_name }}
        </div>
      </div>
      <div v-if="mission.responsable.mobile" class="mb-2 flex">
        <div class="card-label">Portable</div>
        <div class="text-gray-900 flex-1">
          {{ mission.responsable.mobile }}
        </div>
      </div>
      <div v-if="mission.responsable.phone" class="mb-2 flex">
        <div class="card-label">Téléphone</div>
        <div class="text-gray-900 flex-1">
          {{ mission.responsable.phone }}
        </div>
      </div>
    </template>
    <div v-if="mission.participations_max" class="mb-2 flex">
      <div class="card-label">Max.</div>
      <div class="text-gray-900 flex-1">
        {{ mission.participations_max }}
      </div>
    </div>
    <div v-if="mission.information" class="mb-2 flex">
      <div class="card-label">Informations</div>
      <div class="text-gray-900 flex-1">
        <client-only>
          <ReadMore
            more-class="cursor-pointer uppercase font-bold text-xs text-[#242526]"
            more-str="Lire plus"
            :text="mission.information"
            :max-chars="120"
          ></ReadMore>
        </client-only>
      </div>
    </div>
    <div v-if="mission.objectif" class="mb-2 flex">
      <div class="card-label">Objectifs</div>
      <div class="text-gray-900 flex-1">
        <client-only>
          <ReadMore
            more-class="cursor-pointer uppercase font-bold text-xs text-[#242526]"
            more-str="Lire plus"
            :text="mission.objectif"
            :max-chars="120"
          ></ReadMore>
        </client-only>
      </div>
    </div>
    <div v-if="mission.description" class="mb-2 flex">
      <div class="card-label">Règles</div>
      <div class="text-gray-900 flex-1">
        <client-only>
          <ReadMore
            more-class="cursor-pointer uppercase font-bold text-xs text-[#242526]"
            more-str="Lire plus"
            :text="mission.description"
            :max-chars="120"
          ></ReadMore>
        </client-only>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    mission: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {}
  },
  created() {},

  methods: {},
}
</script>
