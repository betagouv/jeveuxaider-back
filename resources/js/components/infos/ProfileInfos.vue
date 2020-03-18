<template>
  <div class="text-xs text-gray-600">
    <div v-if="profile.mobile" class="mb-2 flex">
      <div class="card-label">Membre</div>
      <div class="text-gray-900 flex-1">
        {{ profile.created_at | fromNow }}
      </div>
    </div>
    <div v-if="profile.email" class="mb-2 flex">
      <div class="card-label">Email</div>
      <div class="text-gray-900 flex-1">{{ profile.email }}</div>
    </div>
    <div v-if="profile.mobile" class="mb-2 flex">
      <div class="card-label">Portable</div>
      <div class="text-gray-900 flex-1">{{ profile.mobile }}</div>
    </div>
    <div v-if="profile.phone" class="mb-2 flex">
      <div class="card-label">Téléphone</div>
      <div class="text-gray-900 flex-1">{{ profile.phone }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ProfileInfos",
  props: {
    profile: {
      type: Object,
      required: true
    }
  },
  data() {
    return {};
  },
  created() {},

  methods: {}
};
</script>
