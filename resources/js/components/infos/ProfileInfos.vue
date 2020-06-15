<template>
  <div class="text-xs text-gray-600">
    <div v-if="profile.mobile" class="mb-2 flex">
      <div class="card-label">
        Membre
      </div>
      <div class="text-gray-900 flex-1">
        {{ profile.created_at | fromNow }}
      </div>
    </div>
    <div v-if="profile.email" class="mb-2 flex">
      <div class="card-label">
        Email
      </div>
      <div class="text-gray-900 flex-1">
        {{ profile.email }}
      </div>
    </div>
    <div v-if="profile.mobile" class="mb-2 flex">
      <div class="card-label">
        Portable
      </div>
      <div class="text-gray-900 flex-1">
        {{ profile.mobile }}
      </div>
    </div>
    <div v-if="profile.phone" class="mb-2 flex">
      <div class="card-label">
        Téléphone
      </div>
      <div class="text-gray-900 flex-1">
        {{ profile.phone }}
      </div>
    </div>
    <div v-if="profile.birthday" class="mb-2 flex">
      <div class="card-label">
        Naissance
      </div>
      <div class="text-gray-900 flex-1">
        {{ profile.birthday }}
      </div>
    </div>
    <div v-if="profile.zip" class="mb-2 flex">
      <div class="card-label">
        Code postal
      </div>
      <div class="text-gray-900 flex-1">
        {{ profile.zip }}
      </div>
    </div>
    <div v-if="profile.skills" class="mb-2 flex">
      <div class="card-label">
        Domaines
      </div>
      <div class="text-gray-900 flex-1">
        <span v-for="domaine in profile.domaines" :key="domaine.id">{{
          domaine.name.fr
        }}</span>
      </div>
    </div>
    <div v-if="profile.skills" class="mb-2 flex">
      <div class="card-label">
        Compétences
      </div>
      <div class="text-gray-900 flex-1">
        <span v-for="skill in profile.skills" :key="skill.id">{{
          skill.name.fr
        }}</span>
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
  data() {
    return {}
  },
  created() {
    console.log('volet profile', this.profile)
  },

  methods: {},
}
</script>
