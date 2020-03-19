<template>
  <div class="">
    <div class="text-xs text-gray-600 flex flex-row flex-wrap">
      <div v-if="participation.mission.name" class="card-item mb-2 flex">
        <div class="card-label">Mission</div>
        <div class="text-gray-900 flex-1">{{ participation.mission.name }}</div>
      </div>
      <div v-if="participation.profile.email" class="card-item mb-2 flex">
        <div class="card-label">Email</div>
        <div class="text-gray-900 flex-1">{{ participation.profile.email }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ParticipationInfos",
  props: {
    young: {
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
