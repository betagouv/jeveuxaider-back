<template>
  <Volet>
    <el-card shadow="never" class="overflow-visible mt-24">
      <div slot="header" class="flow-root flex flex-col items-center">
        <div class="-mt-10">
          <Avatar
            :fallback="canShowProfileDetails ? row.profile.short_name : 'XX'"
          />
        </div>
        <nuxt-link
          class="font-bold text-primary text-lg my-3"
          :to="`/dashboard/participation/${row.id}`"
        >
          <span v-if="canShowProfileDetails">
            {{ row.profile.full_name }}
          </span>
          <span v-else>Anonyme</span>
        </nuxt-link>
        <div class="flex items-center">
          <nuxt-link class="mr-1" :to="`/dashboard/participation/${row.id}`">
            <el-button icon="el-icon-view" type="mini">Voir</el-button>
          </nuxt-link>
        </div>
      </div>
      <div class="flex items-center justify-center mb-4">
        <TagModelState
          :state="row.state"
          size="small"
          class="flex items-center"
        />
      </div>
      <ModelParticipationInfos :participation="row" />
    </el-card>
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      form: { ...this.$store.getters['volet/row'] },
    }
  },
  computed: {
    row() {
      return this.$store.getters['volet/row']
    },
    canShowProfileDetails() {
      return !!(
        this.row.mission &&
        (this.row.mission.state != 'Signalée' ||
          this.$store.getters.contextRole !== 'responsable')
      )
    },
    canChangeState() {
      const state = ['En attente de validation', 'Validée']
      return state.includes(this.row.state) === true
    },
    statesAvailable() {
      if (this.$store.getters.contextRole == 'responsable') {
        return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
          (item) => item.value != 'Annulée'
        )
      } else {
        return this.$store.getters.taxonomies.participation_workflow_states
          .terms
      }
    },
  },
  methods: {
    onClickDelete() {
      this.$confirm(
        `La participation sera définitivement supprimée de la plateforme. Voulez-vous continuer ?`,
        'Supprimer la participation',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deleteParticipation(this.row.id).then(() => {
          this.$message.success({
            message: `La participation ${this.row.name} a été supprimée.`,
          })
          this.$emit('deleted', this.row)
          // this.$store.commit('volet/setRow', null)
          this.$store.commit('volet/hide')
        })
      })
    },
    onSubmit() {
      this.$confirm(
        'Êtes vous sur de vos changements ?<br><br> Une notification sera envoyée au réserviste<br><br>',
        'Confirmation',
        {
          confirmButtonText: 'Je confirme',
          cancelButtonText: 'Annuler',
          dangerouslyUseHTMLString: true,
          center: true,
          type: 'warning',
        }
      ).then(() => {
        this.loading = true
        this.$api
          .updateParticipation(this.form.id, this.form)
          .then((response) => {
            this.loading = false
            this.$message.success({
              message: 'La participation a été mise à jour',
            })
            this.$emit('updated', { ...this.form, ...response.data })
            this.$store.commit('volet/setRow', {
              ...this.row,
              ...response.data,
            })
          })
          .catch(() => {
            this.loading = false
          })
      })
    },
  },
}
</script>
