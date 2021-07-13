<template>
  <div class="text-xs text-gray-600 relative">
    <div class="mb-2 flex">
      <div class="card-label">Créée le</div>
      <div class="text-gray-900 flex-1">
        {{ territoire.created_at | formatMediumWithTime }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Modifiée le</div>
      <div class="text-gray-900 flex-1">
        {{ territoire.updated_at | formatMediumWithTime }}
      </div>
    </div>
    <div v-if="territoire.state" class="mb-2 flex">
      <div class="card-label">Statut</div>
      <div class="text-gray-900 flex-1">
        {{ territoire.state | labelFromValue('territoires_states') }}
      </div>
    </div>
    <div v-if="territoire.type" class="mb-2 flex">
      <div class="card-label">Type</div>
      <div class="text-gray-900 flex-1">
        {{ territoire.type | labelFromValue('territoires_types') }}
      </div>
    </div>
    <div v-if="territoire.type" class="mb-2 flex">
      <div class="card-label">Departement</div>
      <div class="text-gray-900 flex-1">
        {{ territoire.department | labelFromValue('departments') }}
      </div>
    </div>
    <div v-if="territoire.zips" class="mb-2 flex">
      <div class="card-label">Codes post.</div>
      <div class="text-gray-900 flex-1">
        {{ territoire.zips.join(', ') }}
      </div>
    </div>
    <!-- <div v-if="territoire.description" class="mb-2 flex">
      <div class="card-label">Description</div>
      <div class="text-gray-900 flex-1">
        <client-only>
          <ReadMore
            more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
            more-str="Lire plus"
            :text="territoire.description"
            :max-chars="120"
          ></ReadMore>
        </client-only>
      </div>
    </div> -->
  </div>
</template>

<script>
export default {
  props: {
    territoire: {
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
