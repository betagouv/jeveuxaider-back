<template>
  <Volet>
    <div class="flex flex-col space-y-6 mt-12">
      <VoletCard
        v-if="participation"
        label="Participation"
        :link="`/dashboard/participation/${participation.id}`"
      >
        <VoletRowItem label="ID">{{ participation.id }}</VoletRowItem>
        <VoletRowItem label="Statut">{{ participation.state }}</VoletRowItem>
        <VoletRowItem label="Crée le">{{
          participation.created_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Modifié le">{{
          participation.updated_at | formatMediumWithTime
        }}</VoletRowItem>
      </VoletCard>

      <VoletCard
        v-if="profile"
        label="Bénévole"
        :link="`/dashboard/profile/${profile.id}`"
      >
        <VoletRowItem label="ID">{{ profile.id }}</VoletRowItem>
        <VoletRowItem label="Nom">{{ profile.full_name }}</VoletRowItem>
        <VoletRowItem label="Type">
          <template v-if="profile.type">
            {{ profile.type | labelFromValue('profile_types') }}
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Nom">
          {{ profile.full_name }}
        </VoletRowItem>
        <template v-if="canShowProfileDetails">
          <VoletRowItem label="Email">{{ profile.email }}</VoletRowItem>
          <VoletRowItem v-if="profile.mobile" label="Mobile">{{
            profile.mobile
          }}</VoletRowItem>
          <VoletRowItem v-if="profile.birthday" label="Anniversaire">{{
            profile.birthday
          }}</VoletRowItem>
          <VoletRowItem v-if="profile.zip" label="Zip">{{
            profile.zip
          }}</VoletRowItem>
        </template>
        <VoletRowItem label="Domaines">
          <template v-if="profile.domaines && profile.domaines.length > 0">
            {{
              profile.domaines
                .map(function (item) {
                  return item.name.fr
                })
                .join(', ')
            }}
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Compétences">
          <template v-if="profile.skills && profile.skills.length > 0">
            {{
              profile.skills
                .map(function (item) {
                  return item.name.fr
                })
                .join(', ')
            }}
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Disponibilités">
          <template
            v-if="profile.disponibilities && profile.disponibilities.length > 0"
          >
            {{
              profile.disponibilities
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
        </VoletRowItem>
        <VoletRowItem label="Crée le">{{
          profile.created_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Modifié le">{{
          profile.updated_at | formatMediumWithTime
        }}</VoletRowItem>

        <VoletRowItem label="Dernière co.">{{
          profile.last_online_at | fromNow
        }}</VoletRowItem>
      </VoletCard>

      <VoletCard
        v-if="mission"
        label="Mission"
        :link="`/dashboard/mission/${mission.id}`"
      >
        <VoletRowItem label="ID">{{ mission.id }}</VoletRowItem>
        <VoletRowItem label="Nom">{{ mission.name }}</VoletRowItem>
        <VoletRowItem label="Statut">{{ mission.state }}</VoletRowItem>
        <VoletRowItem label="Type"> {{ mission.type }} </VoletRowItem>
        <VoletRowItem label="Format"> {{ mission.format }} </VoletRowItem>
        <VoletRowItem v-if="mission.start_date" label="Debut">
          {{ mission.start_date | formatLongWithTime }}</VoletRowItem
        >
        <VoletRowItem v-if="mission.end_date" label="Fin">
          {{ mission.end_date | formatLongWithTime }}
        </VoletRowItem>
        <VoletRowItem label="Adresse">
          {{ mission.full_address }}
        </VoletRowItem>
        <VoletRowItem label="Département">
          {{ mission.department | fullDepartmentFromValue }}
        </VoletRowItem>
        <VoletRowItem label="Information">
          <ReadMore
            more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
            more-str="Lire plus"
            :text="mission.information"
            :max-chars="120"
          ></ReadMore>
        </VoletRowItem>
        <VoletRowItem label="Objectif">
          <ReadMore
            more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
            more-str="Lire plus"
            :text="mission.objectif"
            :max-chars="120"
          ></ReadMore>
        </VoletRowItem>
        <VoletRowItem label="Règles">
          <ReadMore
            more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
            more-str="Lire plus"
            :text="mission.description"
            :max-chars="120"
          ></ReadMore>
        </VoletRowItem>
      </VoletCard>

      <VoletCard
        v-if="responsable"
        label="Responsable"
        :link="`/dashboard/profile/${responsable.id}`"
      >
        <VoletRowItem label="ID">{{ responsable.id }}</VoletRowItem>
        <VoletRowItem label="Nom">{{ responsable.full_name }}</VoletRowItem>
        <VoletRowItem label="Email">{{ responsable.email }}</VoletRowItem>
        <VoletRowItem label="Mobile">{{ responsable.mobile }}</VoletRowItem>
        <VoletRowItem v-if="responsable.phone" label="Tel">{{
          responsable.phone
        }}</VoletRowItem>
      </VoletCard>
    </div>
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      form: { ...this.$store.getters['volet/row'] },
      profile: null,
      participation: null,
      mission: null,
      responsable: null,
    }
  },
  computed: {
    row() {
      return this.$store.getters['volet/row']
    },
    canShowProfileDetails() {
      return !!(
        this.mission &&
        (this.mission.state != 'Signalée' ||
          this.$store.getters.contextRole !== 'responsable')
      )
    },
    // canChangeState() {
    //   const state = ['En attente de validation', 'Validée']
    //   return state.includes(this.row.state) === true
    // },
    // statesAvailable() {
    //   if (this.$store.getters.contextRole == 'responsable') {
    //     return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
    //       (item) => item.value != 'Annulée'
    //     )
    //   } else {
    //     return this.$store.getters.taxonomies.participation_workflow_states
    //       .terms
    //   }
    // },
  },
  watch: {
    row: {
      immediate: true,
      deep: false,
      async handler(newValue, oldValue) {
        this.form = { ...newValue }
        this.participation = await this.$api.getParticipation(this.form.id)
        this.profile = await this.$api.getProfile(this.participation.profile_id)
        this.mission = await this.$api.getMission(this.participation.mission_id)
        this.responsable = await this.$api.getProfile(
          this.mission.responsable_id
        )
      },
    },
  },
  methods: {
    // onClickDelete() {
    //   this.$confirm(
    //     `La participation sera définitivement supprimée de la plateforme. Voulez-vous continuer ?`,
    //     'Supprimer la participation',
    //     {
    //       confirmButtonText: 'Supprimer',
    //       confirmButtonClass: 'el-button--danger',
    //       cancelButtonText: 'Annuler',
    //       center: true,
    //       type: 'error',
    //     }
    //   ).then(() => {
    //     this.$api.deleteParticipation(this.row.id).then(() => {
    //       this.$message.success({
    //         message: `La participation ${this.row.name} a été supprimée.`,
    //       })
    //       this.$emit('deleted', this.row)
    //       // this.$store.commit('volet/setRow', null)
    //       this.$store.commit('volet/hide')
    //     })
    //   })
    // },
    // onSubmit() {
    //   this.$confirm(
    //     'Êtes vous sur de vos changements ?<br><br> Une notification sera envoyée au réserviste<br><br>',
    //     'Confirmation',
    //     {
    //       confirmButtonText: 'Je confirme',
    //       cancelButtonText: 'Annuler',
    //       dangerouslyUseHTMLString: true,
    //       center: true,
    //       type: 'warning',
    //     }
    //   ).then(() => {
    //     this.loading = true
    //     this.$api
    //       .updateParticipation(this.form.id, this.form)
    //       .then((response) => {
    //         this.loading = false
    //         this.$message.success({
    //           message: 'La participation a été mise à jour',
    //         })
    //         this.$emit('updated', { ...this.form, ...response.data })
    //         this.$store.commit('volet/setRow', {
    //           ...this.row,
    //           ...response.data,
    //         })
    //       })
    //       .catch(() => {
    //         this.loading = false
    //       })
    //   })
    // },
  },
}
</script>
