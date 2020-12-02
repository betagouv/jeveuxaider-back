<template>
  <div class="text-xs text-gray-600 relative">
    <div class="mb-2 flex">
      <div class="card-label">Id</div>
      <div class="text-gray-900 flex-1">
        {{ mission.id }}
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
    <div v-if="mission.format" class="mb-2 flex">
      <div class="card-label">Format</div>
      <div class="text-gray-900 flex-1">
        {{ mission.format }}
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
    <div v-if="mission.information" class="mb-2 flex">
      <div class="card-label">Informations</div>
      <div class="text-gray-900 flex-1">
        <ReadMore
          more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
          more-str="Lire plus"
          :text="mission.information"
          :max-chars="120"
        ></ReadMore>
      </div>
    </div>
    <div v-if="mission.objectif" class="mb-2 flex">
      <div class="card-label">Objectifs</div>
      <div class="text-gray-900 flex-1">
        <ReadMore
          more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
          more-str="Lire plus"
          :text="mission.objectif"
          :max-chars="120"
        ></ReadMore>
      </div>
    </div>
    <div v-if="mission.description" class="mb-2 flex">
      <div class="card-label">Règles</div>
      <div class="text-gray-900 flex-1">
        <ReadMore
          more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
          more-str="Lire plus"
          :text="mission.description"
          :max-chars="120"
        ></ReadMore>
      </div>
    </div>
  </div>
</template>

<script>
import ReadMore from '@/components/ReadMore'

export default {
  name: 'MissionInfos',
  components: {
    ReadMore,
  },
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
