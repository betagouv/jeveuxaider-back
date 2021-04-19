<template>
  <div class="relative">
    <template v-if="mission.state">
      <template v-if="mission.state == 'Validée'">
        <template v-if="$store.getters.isLogged && isAlreadyRegistered">
          <nuxt-link to="/user/missions" :class="btnClasses">
            Vous êtes inscrit(e)
          </nuxt-link>
        </template>
        <template v-else>
          <template v-if="mission.has_places_left">
            <button
              v-if="isNotResponsableOfMission"
              :class="btnClasses"
              @click="onClick"
            >
              Je propose mon aide
            </button>
            <nuxt-link
              v-else
              :to="`/dashboard/mission/${mission.id}`"
              :class="btnClasses"
              >Tableau de bord</nuxt-link
            >
          </template>
        </template>
      </template>
      <template v-else>
        <span :class="btnClasses">
          {{ mission.state }}
        </span>
      </template>
    </template>
  </div>
</template>

<script>
export default {
  name: 'ButtonJeProposeMonAide',
  props: {
    mission: {
      type: Object,
      required: true,
    },
    size: {
      type: String,
      default: 'medium',
    },
  },
  data() {
    return {}
  },
  computed: {
    btnClasses() {
      let classes =
        'max-w-sm mx-auto w-full flex items-center justify-center border border-transparent rounded-full text-white bg-green-400 hover:bg-green-500 focus:shadow-outline transition duration-150 ease-in-out'
      if (this.size == 'big') {
        classes += ' px-12 py-3 pb-4 text-2xl leading-9 font-medium'
      } else classes += ' font-bold text-xl px-5 py-3 pb-4'
      return classes
    },
    hasParticipation() {
      return this.$store.getters.isLogged && this.$store.getters.profile
        ? this.$store.getters.profile.participations.filter(
            (participation) =>
              participation.mission_id == this.mission.id &&
              participation.state != 'Annulée'
          )
        : []
    },
    isNotResponsableOfMission() {
      return this.$store.getters.isLogged && this.$store.getters.profile
        ? this.$store.getters.profile.id != this.mission.responsable_id
        : true
    },
    isAlreadyRegistered() {
      return this.hasParticipation.length > 0
    },
  },
  methods: {
    onClick() {
      window.plausible('Click CTA - Mission', {
        props: { isLogged: this.$store.getters.isLogged },
      })
      this.$store.commit('toggleSoftGateOverlay')
      this.$store.commit('setMissionSelected', this.mission)
    },
  },
}
</script>
