<template>
  <div class="text-xs text-gray-600 flex flex-col">
    <div class="mb-2 flex">
      <div class="card-label">Id</div>
      <div class="text-gray-900 flex-1">
        {{ participation.id }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Créée le</div>
      <div class="text-gray-900 flex-1">
        {{ participation.created_at | formatMediumWithTime }}
      </div>
    </div>
    <div class="mb-2 flex">
      <div class="card-label">Modifiée le</div>
      <div class="text-gray-900 flex-1">
        {{ participation.updated_at | formatMediumWithTime }}
      </div>
    </div>
    <template v-if="participation.mission">
      <div v-if="participation.mission.structure" class="card-item mb-2 flex">
        <div class="card-label">Organisation</div>
        <div class="text-gray-900 flex-1">
          {{ participation.mission.structure.name }}
        </div>
      </div>
      <div v-if="participation.mission.responsable" class="card-item mb-2 flex">
        <div class="card-label">Responsable</div>
        <div class="text-gray-900 flex-1">
          {{ participation.mission.responsable.full_name }}
        </div>
      </div>
      <div v-if="participation.mission.name" class="card-item mb-2 flex">
        <div class="card-label">Mission</div>
        <div class="text-gray-900 flex-1">
          {{ participation.mission.name }}
        </div>
      </div>
      <div v-if="participation.mission.name" class="card-item mb-2 flex">
        <div class="card-label">Ville</div>
        <div class="text-gray-900 flex-1">
          {{ participation.mission.city }} - ({{ participation.mission.zip }})
        </div>
      </div>
      <div v-if="participation.mission.type" class="card-item mb-2 flex">
        <div class="card-label">Type</div>
        <div class="text-gray-900 flex-1">
          {{ participation.mission.type }}
        </div>
      </div>
      <div v-if="participation.mission.format" class="card-item mb-2 flex">
        <div class="card-label">Format</div>
        <div class="text-gray-900 flex-1">
          {{ participation.mission.format }}
        </div>
      </div>
    </template>
    <template
      v-if="
        participation.profile &&
        participation.mission &&
        (participation.mission.state != 'Signalée' ||
          $store.getters.contextRole !== 'responsable')
      "
    >
      <div v-if="participation.profile.email" class="card-item mb-2 flex">
        <div class="card-label">E-mail</div>
        <div class="text-gray-900 flex-1">
          {{ participation.profile.email }}
        </div>
      </div>
      <div v-if="participation.profile.email" class="card-item mb-2 flex">
        <div class="card-label">Bénévole</div>
        <div class="text-gray-900 flex-1">
          {{ participation.profile.full_name }}
        </div>
      </div>
      <div v-if="participation.profile.email" class="card-item mb-2 flex">
        <div class="card-label">Mobile</div>
        <div class="text-gray-900 flex-1">
          {{ participation.profile.mobile }}
        </div>
      </div>
      <div v-if="participation.profile.birthday" class="card-item mb-2 flex">
        <div class="card-label">Anniversaire</div>
        <div class="text-gray-900 flex-1">
          {{ participation.profile.birthday }}
        </div>
      </div>
      <div v-if="participation.profile.zip" class="card-item mb-2 flex">
        <div class="card-label">Code postal</div>
        <div class="text-gray-900 flex-1">
          {{ participation.profile.zip }}
        </div>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    participation: {
      type: Object,
      required: true,
    },
  },
}
</script>
