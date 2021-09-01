<template>
  <div class="relative">
    <template v-if="mission.state == 'Validée'">
      <nuxt-link
        v-if="$store.getters.isLogged && isAlreadyRegistered"
        to="/user/missions"
        :class="btnClasses"
      >
        Vous êtes inscrit(e)
      </nuxt-link>

      <nuxt-link
        v-else-if="isResponsableOfMission"
        :to="`/dashboard/mission/${mission.id}`"
        :class="btnClasses"
      >
        Tableau de bord
      </nuxt-link>

      <button
        v-else-if="mission.has_places_left"
        :class="btnClasses"
        @click="onClick"
      >
        Je propose mon aide
      </button>
    </template>

    <span v-else :class="btnClasses">
      {{ mission.state }}
    </span>
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
    additionalBtnClasses: {
      type: String,
      default: '',
    },
  },
  data() {
    return {}
  },
  computed: {
    btnClasses() {
      const classes =
        'max-w-sm mx-auto w-full flex items-center justify-center border border-transparent rounded-full text-white bg-green-400 hover:scale-105 focus:outline-none focus:scale-105 transition duration-150 ease-in-out font-extrabold text-xl px-5 py-3 pb-4 transform will-change-transform'
      return [classes, this.additionalBtnClasses].join(' ')
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
    isResponsableOfMission() {
      return this.$store.getters.isLogged && this.$store.getters.profile
        ? this.$store.getters.profile.id == this.mission.responsable_id
        : false
    },
    isAlreadyRegistered() {
      return this.hasParticipation.length > 0
    },
  },
  methods: {
    onClick() {
      window.plausible &&
        window.plausible('Click CTA - Mission', {
          props: { isLogged: this.$store.getters.isLogged },
        })
      this.$store.commit('toggleSoftGateOverlay')
      this.$store.commit('setMissionSelected', this.mission)
    },
  },
}
</script>
