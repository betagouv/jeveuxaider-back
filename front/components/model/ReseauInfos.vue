<template>
  <div class="text-xs text-gray-600 relative">
    <div class="mb-2 flex">
      <div class="card-label">Id</div>
      <div class="text-gray-900 flex-1">
        {{ reseau.id }}
      </div>
    </div>

    <div class="mb-2 flex">
      <div class="card-label">Créé le</div>
      <div class="text-gray-900 flex-1">
        {{ reseau.created_at | formatMediumWithTime }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Modifié le</div>
      <div class="text-gray-900 flex-1">
        {{ reseau.updated_at | formatMediumWithTime }}
      </div>
    </div>
    <div v-if="reseau.state" class="mb-2 flex">
      <div class="card-label">Statut</div>
      <div class="text-gray-900 flex-1">
        {{ reseau.state }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">NB Organisations</div>
      <div class="text-gray-900 flex-1">{{ reseau.structures_count }}</div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">NB Modèle de mission</div>
      <div class="text-gray-900 flex-1">
        {{ reseau.mission_templates_count }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">NB Mission</div>
      <div class="text-gray-900 flex-1">todo</div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">NB Particip.</div>
      <div class="text-gray-900 flex-1">todo</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    reseau: {
      type: Object,
      required: true,
    },
  },
}
</script>
