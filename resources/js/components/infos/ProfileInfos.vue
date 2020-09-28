<template>
  <div class="text-xs text-gray-600">
    <div v-if="profile.created_at" class="mb-2 flex">
      <div class="card-label">Membre</div>
      <div class="text-gray-900 flex-1">
        {{ profile.created_at | fromNow }}
      </div>
    </div>
    <div v-if="profile.email && canViewPrivateData" class="mb-2 flex">
      <div class="card-label">Email</div>
      <div class="text-gray-900 flex-1">
        {{ profile.email }}
      </div>
    </div>
    <div v-if="profile.mobile && canViewPrivateData" class="mb-2 flex">
      <div class="card-label">Portable</div>
      <div class="text-gray-900 flex-1">
        {{ profile.mobile }}
      </div>
    </div>
    <div v-if="profile.phone && canViewPrivateData" class="mb-2 flex">
      <div class="card-label">Téléphone</div>
      <div class="text-gray-900 flex-1">
        {{ profile.phone }}
      </div>
    </div>
    <div v-if="profile.birthday && canViewPrivateData" class="mb-2 flex">
      <div class="card-label">Naissance</div>
      <div class="text-gray-900 flex-1">
        {{ profile.birthday }}
      </div>
    </div>
    <div v-if="profile.zip" class="mb-2 flex">
      <div class="card-label">Code postal</div>
      <div class="text-gray-900 flex-1">
        {{ profile.zip }}
      </div>
    </div>
    <div
      v-if="profile.domaines && profile.domaines.length > 0"
      class="mb-2 flex"
    >
      <div class="card-label">Domaines</div>
      <div class="text-gray-900 flex-1">
        {{
          profile.domaines
            .map(function (item) {
              return item.name.fr
            })
            .join(', ')
        }}
      </div>
    </div>
    <div v-if="profile.skills && profile.skills.length > 0" class="mb-2 flex">
      <div class="card-label">Compétences</div>
      <div class="text-gray-900 flex-1">
        {{
          profile.skills
            .map(function (item) {
              return item.name.fr
            })
            .join(', ')
        }}
      </div>
    </div>
    <div v-if="profile.disponibilities" class="mb-2 flex">
      <div class="card-label">Dispos</div>
      <div class="text-gray-900 flex-1">
        {{ profile.disponibilities.join(', ') }}
      </div>
    </div>
    <div v-if="profile.frequence" class="mb-2 flex">
      <div class="card-label">Durée</div>
      <div class="text-gray-900 flex-1">
        {{ profile.frequence }} {{ profile.frequence_granularite }}
      </div>
    </div>
    <div v-if="profile.description" class="mb-2 flex">
      <div class="card-label">Motivation</div>
      <div class="text-gray-900 flex-1">
        {{ profile.description }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProfileInfos',
  props: {
    profile: {
      type: Object,
      required: true,
    },
  },
  computed: {
    canViewPrivateData() {
      return (
        this.$store.getters.contextRole === 'admin' ||
        this.$store.getters.contextRole === 'referent' ||
        this.$store.getters.contextRole === 'referent_regional'
      )
    },
  },
}
</script>
