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
      <div
        v-if="
          participation.profile.domaines &&
          participation.profile.domaines.length > 0
        "
        class="mb-2 flex"
      >
        <div class="card-label">Domaines</div>
        <div class="text-gray-900 flex-1">
          {{
            participation.profile.domaines
              .map(function (item) {
                return item.name.fr
              })
              .join(', ')
          }}
        </div>
      </div>
      <div class="mb-2 flex">
        <div class="card-label">Compétences</div>
        <div class="text-gray-900 flex-1">
          <template
            v-if="
              participation.profile.skills &&
              participation.profile.skills.length > 0
            "
          >
            {{
              participation.profile.skills
                .map(function (item) {
                  return item.name.fr
                })
                .join(', ')
            }}
          </template>
          <template v-else> N/A </template>
        </div>
      </div>
      <div class="mb-2 flex">
        <div class="card-label">Dispos</div>
        <div class="text-gray-900 flex-1">
          <template
            v-if="
              participation.profile.disponibilities &&
              participation.profile.disponibilities.length > 0
            "
          >
            {{
              participation.profile.disponibilities
                .map(function (item) {
                  return $options.filters.labelFromValue(
                    item,
                    'profile_disponibilities'
                  )
                })
                .join(', ')
            }}
          </template>
          <template v-else> N/A </template>
        </div>
      </div>
      <div class="mb-2 flex">
        <div class="card-label">Fréquence</div>
        <div class="text-gray-900 flex-1">
          <template v-if="participation.profile.frequence">
            {{ participation.profile.frequence }}
            {{ participation.profile.frequence_granularite }}
          </template>
          <template v-else> N/A </template>
        </div>
      </div>
      <div class="mb-2 flex">
        <div class="card-label">Motivation</div>
        <div class="text-gray-900 flex-1">
          <template v-if="participation.profile.description">
            {{ participation.profile.description }}
          </template>
          <template v-else> N/A </template>
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
