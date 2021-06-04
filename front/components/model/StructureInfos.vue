<template>
  <div class="text-xs text-gray-600 relative">
    <div class="mb-2 flex">
      <div class="card-label">Id</div>
      <div class="text-gray-900 flex-1">
        {{ structure.id }}
      </div>
    </div>
    <div v-if="structure.statut_juridique == 'Association'" class="mb-2 flex">
      <div class="card-label">Page</div>

      <nuxt-link
        class="text-gray-900 flex-1 hover:underline"
        target="_blank"
        :to="`/organisations/${structure.slug}`"
      >
        /organisations/{{ structure.slug }}
      </nuxt-link>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">RNA</div>
      <div class="text-gray-900 flex-1">
        <template v-if="structure.rna">
          {{ structure.rna }}
        </template>
        <template v-else> N/A </template>
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Créée le</div>
      <div class="text-gray-900 flex-1">
        {{ structure.created_at | formatMediumWithTime }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Modifiée le</div>
      <div class="text-gray-900 flex-1">
        {{ structure.updated_at | formatMediumWithTime }}
      </div>
    </div>
    <div v-if="structure.state" class="mb-2 flex">
      <div class="card-label">Statut</div>
      <div class="text-gray-900 flex-1">
        {{ structure.state }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Taux réponse</div>
      <div class="text-gray-900 flex-1">
        <template v-if="structure.response_ratio !== null">
          <span class="">{{ structure.response_ratio }}%</span>

          <!-- <i
            v-tooltip="{
              content: `${
                structure.participations_count -
                structure.waiting_participations_count
              } / ${structure.participations_count} participations`,
              classes: 'bo-style',
            }"
            class="el-icon-info"
          /> -->
        </template>
        <template v-else> N/A </template>
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Temps réponse</div>
      <div class="text-gray-900 flex-1">
        <template v-if="structure.response_time !== null">
          {{ structure.response_time | daysFromTimestamp }} jours
        </template>
        <template v-else> N/A </template>
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Score Temps rép.</div>
      <div class="text-gray-900 flex-1">
        {{ structure.response_time_score }}/100
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">NB Mission</div>
      <div class="text-gray-900 flex-1">
        {{ structure.missions_count }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">NB Particip.</div>
      <div class="text-gray-900 flex-1">
        {{ structure.participations_count }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">NB Convers.</div>
      <div class="text-gray-900 flex-1">
        {{ structure.conversations_count }}
      </div>
    </div>
    <div v-if="structure.statut_juridique" class="mb-2 flex">
      <div class="card-label">Statut</div>
      <div class="text-gray-900 flex-1">
        {{ structure.statut_juridique }}
      </div>
    </div>
    <div v-if="structure.structure_publique_type" class="mb-2 flex">
      <div class="card-label">Type</div>
      <div class="text-gray-900 flex-1">
        {{ structure.structure_publique_type }}
      </div>
    </div>
    <div v-if="structure.association_types" class="mb-2 flex">
      <div class="card-label">Agréements</div>
      <div class="text-gray-900 flex-1">
        {{ structure.association_types.join(', ') }}
      </div>
    </div>
    <div v-if="structure.structure_publique_etat_type" class="mb-2 flex">
      <div class="card-label">Corps</div>
      <div class="text-gray-900 flex-1">
        {{ structure.structure_publique_etat_type }}
      </div>
    </div>
    <div v-if="structure.full_address" class="mb-2 flex">
      <div class="card-label">Adresse</div>
      <div class="text-gray-900 flex-1">
        {{ structure.full_address }}
      </div>
    </div>
    <div v-if="structure.department" class="mb-2 flex">
      <div class="card-label">Département</div>
      <div class="text-gray-900 flex-1">
        {{ structure.department | fullDepartmentFromValue }}
      </div>
    </div>
    <div v-if="structure.description" class="mb-2 flex">
      <div class="card-label">Description</div>
      <div class="text-gray-900 flex-1">
        <client-only>
          <ReadMore
            more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
            more-str="Lire plus"
            :text="structure.description"
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
    structure: {
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
