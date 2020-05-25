<template>
  <div
    v-if="mission"
    v-show="!$store.getters.loading"
    class="mission-form pl-12 pb-12"
  >
    <div class="text-m text-gray-600 uppercase">Mission</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl text-gray-800 max-w-3xl">
        {{ mission.name }}
      </div>
      <state-tag
        :state="mission.state"
        class="relative ml-3"
        style="top: 1px;"
      ></state-tag>
    </div>

    <MissionForm :mission="mission" :structureId="mission.structure_id" />
  </div>
</template>

<script>
import { getMission } from '@/api/mission'
import MissionForm from '@/views/SNU/MissionForm'
import StateTag from '@/components/StateTag'

export default {
  name: 'MissionEdit',
  components: {
    MissionForm,
    StateTag,
  },
  props: {
    id: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      mission: null,
    }
  },
  created() {
    this.$store.commit('setLoading', true)
    getMission(this.id).then((response) => {
      this.mission = response.data
      this.$store.commit('setLoading', false)
    })
  },
}
</script>
