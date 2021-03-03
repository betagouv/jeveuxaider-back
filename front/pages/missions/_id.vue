<template>
  <div>coucou</div>
</template>

<script>
// import { fetchStructureAvailableMissions } from '@/api/structure'
import dayjs from 'dayjs'

export default {
  name: 'Mission',
  props: {
    id: {
      type: Number,
      default: null,
    },
  },
  async asyncData({ $api, params }) {
    const mission = await $api.getMission(params.id)
    // if (mission.responsable && this.$store.getters.profile) {
    //   this.form.content = `Bonjour ${this.mission.responsable.first_name},\nJe souhaite participer à cette mission et apporter mon aide. \nJe me tiens disponible pour échanger et débuter la mission 🙂\n${this.$store.getters.profile.first_name}`
    // }
    // fetchStructureAvailableMissions(this.mission.structure.id, {
    //   exclude: this.id,
    //   append: 'domaines',
    // })
    //   .then((response) => {
    //     this.otherMissions = response.data
    //   })
    //   .catch(() => {
    //     this.loading = false
    //   })
    return {
      mission,
    }
  },
  metaInfo() {
    return {
      title: this.mission.name
        ? 'Bénévolat pour ' + this.structure.name + ' | ' + this.mission.name
        : this.mission.name,
      meta: [
        {
          name: 'description',
          content:
            this.structure && this.structure.description
              ? this.$options.filters.truncate(
                  this.structure.description.replace(/<\/?[^>]+>/gi, ' '),
                  156
                )
              : '',
        },
      ],
    }
  },
  data() {
    return {
      loading: true,
      mission: {},
      otherMissions: {},
      baseUrl: process.env.MIX_API_BASE_URL,
      dialogParticipateVisible:
        Boolean(this.$route.query.showDialogParticipate) || false,
      dialogProposerAide: false,
      dialogLoading: false,
      form: {
        content: `Bonjour,\nJe souhaite participer à cette mission et apporter mon aide. \nJe me tiens disponible pour échanger et débuter la mission 🙂\n`,
      },
      rules: {
        content: [
          {
            required: true,
            message: 'Entrez un message.',
            trigger: 'blur',
          },
          {
            min: 10,
            message: 'Votre message est trop court.',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  computed: {
    structure() {
      return this.mission.structure
    },
    structureType() {
      const status = this.$options.filters
        .labelFromValue(
          this.structure.statut_juridique,
          'structure_legal_status'
        )
        .toLowerCase()
      return `L'${status}`
    },
    hasParticipation() {
      return this.$store.getters.profile.participations.filter(
        (participation) =>
          participation.mission_id == this.id &&
          participation.state != 'Annulée'
      )
    },
    isNotResponsableOfMission() {
      return this.$store.getters.profile.id != this.mission.responsable_id
    },
    isAlreadyRegistered() {
      return !(this.hasParticipation.length > 0)
    },
    responseTime() {
      const daysDelay = this.$options.filters.daysFromTimestamp(
        this.mission.structure.response_time
      )
      if (daysDelay < 5) {
        return 'Moins de 5 jours'
      } else if (daysDelay < 10) {
        return 'Entre 5 et 10 jours'
      }
      return 'Moins de 15 jours'
    },
    formattedDate() {
      const startDate = this.mission.start_date
      const endDate = this.mission.end_date

      if (!endDate) {
        return null
      }

      if (dayjs(startDate).format('YYYY') != dayjs(endDate).format('YYYY')) {
        return `Du <b class="text-white">${dayjs(startDate).format(
          'D MMMM YYYY'
        )}</b> au <b class="text-white">${dayjs(endDate).format(
          'D MMMM YYYY'
        )}</b>`
      } else {
        return `Du <b class="text-white">${dayjs(startDate).format(
          'D MMMM'
        )}</b> au <b class="text-white">${dayjs(endDate).format(
          'D MMMM YYYY'
        )}</b>`
      }
    },
  },
  methods: {
    // handleSubmitFormParticipate() {
    //   this.$refs.participateForm.validate((valid) => {
    //     if (valid) {
    //       this.dialogLoading = true
    //       addParticipation(
    //         this.mission.id,
    //         this.$store.getters.profile.id,
    //         this.form.content
    //       )
    //         .then(() => {
    //           this.dialogLoading = false
    //           this.$router.push('/messages')
    //           console.log('Go tracket api engagement', window.apieng)
    //           window.apieng && window.apieng('trackApplication')
    //           this.$message({
    //             message:
    //               'Votre participation a été enregistrée et est en attente de validation !',
    //             type: 'success',
    //           })
    //           this.loading = false
    //         })
    //         .catch(() => {
    //           this.loading = false
    //         })
    //     }
    //   })
    // },
    // handleClickParticipate() {
    //   this.dialogParticipateVisible = true
    // },
    domainName(mission) {
      return mission.domaine && mission.domaine.name && mission.domaine.name.fr
        ? mission.domaine.name.fr
        : mission.template &&
          mission.template.domaine &&
          mission.template.domaine.name &&
          mission.template.domaine.name.fr
        ? mission.template.domaine.name.fr
        : null
    },
  },
}
</script>

<style lang="sass" scoped>
.aside
  @screen lg
    max-width: 410px
    @apply flex-none w-full

.comment-wrapper
  min-height: 200px
  ::v-deep ul,::v-deep ol
    @apply flex flex-col items-center
  @screen lg
    @apply relative
  .comment-wrapper--icon
    @apply hidden
    @screen lg
      @apply block
    @screen xl
      left: 5%

::v-deep .el-dialog__title
  @apply text-gray-800 text-xl font-bold
</style>
