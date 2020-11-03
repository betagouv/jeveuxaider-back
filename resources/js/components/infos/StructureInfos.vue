<template>
  <div class="text-xs text-gray-600 relative">
    <div v-if="structure.state" class="mb-2 flex">
      <div class="card-label">
        Statut
      </div>
      <div class="text-gray-900 flex-1">
        {{ structure.state }}
      </div>
    </div>
    <div v-if="structure.response_ratio !== null" class="mb-2 flex">
      <div class="card-label">
        Tx réponse
      </div>
      <div class="text-gray-900 flex-1">{{ structure.response_ratio }}%</div>
    </div>
    <div v-if="structure.statut_juridique" class="mb-2 flex">
      <div class="card-label">
        Statut
      </div>
      <div class="text-gray-900 flex-1">
        {{ structure.statut_juridique }}
      </div>
    </div>
    <div v-if="structure.structure_publique_type" class="mb-2 flex">
      <div class="card-label">
        Type
      </div>
      <div class="text-gray-900 flex-1">
        {{ structure.structure_publique_type }}
      </div>
    </div>
    <div v-if="structure.association_types" class="mb-2 flex">
      <div class="card-label">
        Agréements
      </div>
      <div class="text-gray-900 flex-1">
        {{ structure.association_types.join(', ') }}
      </div>
    </div>
    <div v-if="structure.structure_publique_etat_type" class="mb-2 flex">
      <div class="card-label">
        Corps
      </div>
      <div class="text-gray-900 flex-1">
        {{ structure.structure_publique_etat_type }}
      </div>
    </div>
    <div v-if="structure.full_address" class="mb-2 flex">
      <div class="card-label">
        Adresse
      </div>
      <div class="text-gray-900 flex-1">
        {{ structure.full_address }}
      </div>
    </div>
    <div v-if="structure.department" class="mb-2 flex">
      <div class="card-label">
        Département
      </div>
      <div class="text-gray-900 flex-1">
        {{ structure.department | fullDepartmentFromValue }}
      </div>
    </div>
    <div v-if="structure.description" class="mb-2 flex">
      <div class="card-label">
        Description
      </div>
      <div class="text-gray-900 flex-1">
        <ReadMore
          more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
          more-str="Lire plus"
          :text="structure.description"
          :max-chars="120"
        ></ReadMore>
      </div>
    </div>
  </div>
</template>

<script>
import ReadMore from '@/components/ReadMore'

export default {
  name: 'StructureInfos',
  components: {
    ReadMore,
  },
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
